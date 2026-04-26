<?php $__env->startSection('title', 'Contact Us – Peace Institute'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Page hero ───────────────────────────────────────────── */
.contact-hero{
    background:linear-gradient(160deg,var(--gd) 0%,var(--green) 100%);
    padding:5rem 1.5rem 4rem;
    text-align:center;
    position:relative;overflow:hidden;
}
.contact-hero::before{
    content:'تواصل معنا';
    font-family:'Amiri',serif;font-size:7rem;
    color:rgba(255,255,255,.04);
    position:absolute;top:50%;left:50%;
    transform:translate(-50%,-50%);
    white-space:nowrap;pointer-events:none;
}
.contact-hero h1{
    font-family:'Playfair Display',serif;
    font-size:clamp(2rem,5vw,3rem);font-weight:700;
    color:#fff;margin-bottom:.75rem;position:relative;z-index:1;
}
.contact-hero h1 span{color:var(--goldl);}
.contact-hero p{
    color:rgba(255,255,255,.75);font-size:1rem;
    max-width:500px;margin:0 auto;position:relative;z-index:1;
}

/* ── Body ────────────────────────────────────────────────── */
.contact-body{
    max-width:1100px;margin:0 auto;
    padding:3.5rem 1.5rem 5rem;
}

/* ── Cards grid ──────────────────────────────────────────── */
.contact-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:1.5rem;
}
@media(max-width:720px){.contact-grid{grid-template-columns:1fr;}}

.contact-card{
    background:#fff;
    border-radius:24px;
    box-shadow:0 2px 20px rgba(26,107,60,.08);
    border:1.5px solid #EDE9E0;
    padding:2rem;
}
.cc-title{
    font-size:1.2rem;font-weight:800;color:var(--text);
    margin-bottom:1.25rem;display:flex;align-items:center;gap:.6rem;
}
.cc-title i{color:var(--green);}

/* Phone block */
.phone-block{
    display:flex;align-items:center;gap:1rem;
    padding:1rem 1.25rem;
    border-radius:14px;
    background:rgba(26,107,60,.05);
    border:1.5px solid rgba(26,107,60,.12);
    text-decoration:none;
    transition:background .2s,transform .2s;
    margin-bottom:1.25rem;
}
.phone-block:hover{background:rgba(26,107,60,.1);transform:translateY(-2px);}
.phone-icon{
    width:48px;height:48px;flex-shrink:0;
    border-radius:12px;background:var(--green);
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:.95rem;
}
.phone-label{font-size:.75rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.05em;}
.phone-number{font-size:1.05rem;font-weight:700;color:var(--text);margin-top:.1rem;}

/* WhatsApp button */
.wa-btn{
    display:flex;align-items:center;justify-content:center;gap:.6rem;
    width:100%;padding:.8rem;border-radius:12px;
    background:#25D366;color:#fff;
    font-weight:700;font-size:.9rem;
    text-decoration:none;
    transition:background .2s,transform .15s;
    margin-bottom:1.25rem;
}
.wa-btn:hover{background:#1DA851;transform:translateY(-1px);}
.wa-btn i{font-size:1.1rem;}

/* Social links */
.social-list{display:flex;flex-direction:column;gap:.6rem;}
.social-link{
    display:flex;align-items:center;justify-content:space-between;
    padding:.85rem 1rem;
    border-radius:12px;
    border:1.5px solid #EDE9E0;
    text-decoration:none;
    transition:border-color .2s,background .2s;
}
.social-link:hover{border-color:var(--green);background:rgba(26,107,60,.03);}
.social-left{display:flex;align-items:center;gap:.75rem;font-size:.875rem;color:var(--text);font-weight:500;}
.social-left i{font-size:1rem;width:20px;text-align:center;}
.social-arrow{color:var(--muted);font-size:.75rem;}

/* Right card: Why contact */
.why-list{display:flex;flex-direction:column;gap:.75rem;margin-bottom:1.5rem;}
.why-item{
    display:flex;align-items:flex-start;gap:.75rem;
    font-size:.875rem;color:#374151;line-height:1.5;
}
.why-icon{
    width:28px;height:28px;flex-shrink:0;
    border-radius:8px;background:rgba(26,107,60,.1);
    display:flex;align-items:center;justify-content:center;
    color:var(--green);font-size:.7rem;margin-top:.05rem;
}

/* Info banner */
.info-banner{
    background:linear-gradient(135deg,var(--gd) 0%,var(--green) 100%);
    border-radius:16px;padding:1.5rem;
    display:flex;gap:1rem;align-items:flex-start;
}
.ib-icon{
    width:40px;height:40px;flex-shrink:0;
    border-radius:10px;background:rgba(255,255,255,.15);
    display:flex;align-items:center;justify-content:center;
    color:var(--goldl);font-size:.9rem;
}
.ib-title{font-size:.875rem;font-weight:700;color:#fff;margin-bottom:.3rem;}
.ib-body{font-size:.825rem;color:rgba(255,255,255,.75);line-height:1.55;}

/* Bottom CTA strip */
.contact-cta{
    margin-top:2.5rem;
    background:#fff;
    border-radius:24px;
    border:1.5px solid #EDE9E0;
    padding:2rem 2.5rem;
    display:flex;align-items:center;justify-content:space-between;
    flex-wrap:wrap;gap:1.25rem;
}
.cta-left h3{font-size:1.1rem;font-weight:800;color:var(--text);margin-bottom:.3rem;}
.cta-left p{font-size:.875rem;color:var(--muted);}
.cta-right{display:flex;gap:.75rem;flex-wrap:wrap;}
.btn-green-sm{
    padding:.65rem 1.5rem;border-radius:10px;
    background:var(--green);color:#fff;
    font-weight:700;font-size:.875rem;text-decoration:none;
    transition:background .2s;white-space:nowrap;
}
.btn-green-sm:hover{background:var(--gl);}
.btn-outline-sm{
    padding:.65rem 1.5rem;border-radius:10px;
    border:1.5px solid var(--green);color:var(--green);
    font-weight:700;font-size:.875rem;text-decoration:none;
    transition:background .2s,color .2s;white-space:nowrap;
}
.btn-outline-sm:hover{background:var(--green);color:#fff;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="contact-hero">
    <h1>Contact <span>Peace Institute</span></h1>
    <p>Have questions about classes, scheduling, or registration? We're here to help.</p>
</div>


<div class="contact-body">

    <div class="contact-grid">

        
        <div class="contact-card reveal">
            <h2 class="cc-title"><i class="fas fa-headset"></i> Get in Touch</h2>

            <a href="tel:+923022702808" class="phone-block">
                <div class="phone-icon"><i class="fas fa-phone"></i></div>
                <div>
                    <div class="phone-label">Phone / WhatsApp</div>
                    <div class="phone-number">+92 302 2702808</div>
                </div>
            </a>

            <a href="https://wa.me/923022702808?text=Assalamu+Alaykum!+I+want+to+know+more+about+Peace+Institute+classes."
               target="_blank" rel="noopener noreferrer" class="wa-btn">
                <i class="fab fa-whatsapp"></i> Chat on WhatsApp
            </a>

            <div class="social-list">
                <a href="https://www.facebook.com/peaceinstituteglobal"
                   target="_blank" rel="noopener noreferrer" class="social-link">
                    <span class="social-left">
                        <i class="fab fa-facebook" style="color:#1877F2;"></i> Facebook Page
                    </span>
                    <i class="fas fa-arrow-up-right-from-square social-arrow"></i>
                </a>
                <a href="https://www.instagram.com/peaceinstituteglobal/"
                   target="_blank" rel="noopener noreferrer" class="social-link">
                    <span class="social-left">
                        <i class="fab fa-instagram" style="color:#E1306C;"></i> Instagram
                    </span>
                    <i class="fas fa-arrow-up-right-from-square social-arrow"></i>
                </a>
                <a href="https://www.youtube.com/@peaceinstituteglobal"
                   target="_blank" rel="noopener noreferrer" class="social-link">
                    <span class="social-left">
                        <i class="fab fa-youtube" style="color:#FF0000;"></i> YouTube Channel
                    </span>
                    <i class="fas fa-arrow-up-right-from-square social-arrow"></i>
                </a>
            </div>
        </div>

        
        <div class="contact-card reveal">
            <h2 class="cc-title"><i class="fas fa-circle-question"></i> How Can We Help?</h2>

            <div class="why-list">
                <div class="why-item">
                    <div class="why-icon"><i class="fas fa-book-open"></i></div>
                    Course guidance for beginners through advanced levels
                </div>
                <div class="why-item">
                    <div class="why-icon"><i class="fas fa-clock"></i></div>
                    Flexible class scheduling to match your timezone
                </div>
                <div class="why-item">
                    <div class="why-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                    Help selecting the right teacher &amp; arranging trial classes
                </div>
                <div class="why-item">
                    <div class="why-icon"><i class="fas fa-credit-card"></i></div>
                    Assistance with registration, billing &amp; Zoom setup
                </div>
                <div class="why-item">
                    <div class="why-icon"><i class="fas fa-child"></i></div>
                    Special support for kids and elderly students
                </div>
            </div>

            <div class="info-banner">
                <div class="ib-icon"><i class="fas fa-bolt"></i></div>
                <div>
                    <div class="ib-title">Quick Response</div>
                    <div class="ib-body">We typically reply within a few hours on WhatsApp and social media. Click any platform to start a conversation now.</div>
                </div>
            </div>
        </div>

    </div>

    
    <div class="contact-cta reveal">
        <div class="cta-left">
            <h3>Ready to start your Quranic journey?</h3>
            <p>Enroll today — your first trial class is completely free.</p>
        </div>
        <div class="cta-right">
            <a href="<?php echo e(route('register', 'student')); ?>" class="btn-green-sm">
                <i class="fas fa-graduation-cap mr-2"></i> Enroll Free
            </a>
            <a href="<?php echo e(route('teachers')); ?>" class="btn-outline-sm">
                Browse Teachers
            </a>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Peace Institute\Website\resources\views/contact.blade.php ENDPATH**/ ?>