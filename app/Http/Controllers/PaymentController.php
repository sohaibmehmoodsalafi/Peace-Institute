<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create Stripe PaymentIntent for a booking.
     */
    public function createIntent(Request $request)
    {
        $request->validate(['booking_id' => 'required|exists:bookings,id']);

        $booking = Booking::findOrFail($request->booking_id);
        abort_if($booking->student_id !== auth()->user()->student->id, 403);

        $intent = PaymentIntent::create([
            'amount'   => (int) ($booking->amount * 100), // cents
            'currency' => 'usd',
            'metadata' => [
                'booking_ref' => $booking->booking_ref,
                'student_id'  => $booking->student_id,
            ],
        ]);

        // Create pending payment record
        Payment::create([
            'student_id'             => $booking->student_id,
            'booking_id'             => $booking->id,
            'amount'                 => $booking->amount,
            'payment_method'         => 'stripe',
            'status'                 => 'pending',
            'stripe_payment_intent'  => $intent->id,
        ]);

        return response()->json(['client_secret' => $intent->client_secret]);
    }

    /**
     * Handle Stripe webhook events.
     */
    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
        } catch (\Exception $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent  = $event->data->object;
            $payment = Payment::where('stripe_payment_intent', $intent->id)->first();

            if ($payment) {
                $payment->update([
                    'status'           => 'completed',
                    'stripe_charge_id' => $intent->latest_charge,
                    'paid_at'          => now(),
                ]);

                // Update student total spent
                $payment->student->increment('total_spent', $payment->amount);
            }
        }

        return response('OK', 200);
    }

    /**
     * Manual payment record (admin or offline payment).
     */
    public function manual(Request $request, Booking $booking)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate(['notes' => 'nullable|string']);

        Payment::create([
            'student_id'     => $booking->student_id,
            'booking_id'     => $booking->id,
            'amount'         => $booking->amount,
            'payment_method' => 'manual',
            'status'         => 'completed',
            'notes'          => $request->notes,
            'paid_at'        => now(),
        ]);

        $booking->student->increment('total_spent', $booking->amount);

        return back()->with('success', 'Manual payment recorded.');
    }
}
