<?php $__env->startSection('title', 'Our Teachers – Peace Institute'); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* ── Page Hero ───────────────────────────────────────────── */
.tlist-hero{
    background:linear-gradient(160deg,var(--gd) 0%,var(--green) 100%);
    padding:5rem 1.5rem 4rem;
    text-align:center;
    position:relative;overflow:hidden;
}
.tlist-hero::before{
    content:'المعلمون';
    font-family:'Amiri',serif;font-size:8rem;
    color:rgba(255,255,255,.04);
    position:absolute;top:50%;left:50%;
    transform:translate(-50%,-50%);
    white-space:nowrap;pointer-events:none;
}
.tlist-hero h1{
    font-family:'Playfair Display',serif;
    font-size:clamp(2rem,5vw,3rem);font-weight:700;
    color:#fff;margin-bottom:.75rem;
}
.tlist-hero h1 span{color:var(--goldl);}
.tlist-hero p{color:rgba(255,255,255,.75);font-size:1rem;max-width:520px;margin:0 auto 2rem;}

/* Filter bar */
.filter-bar{
    display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;justify-content:center;
}
.filter-chip{
    padding:.4rem 1rem;border-radius:99px;font-size:.8rem;font-weight:600;
    border:1.5px solid rgba(255,255,255,.25);color:rgba(255,255,255,.75);
    text-decoration:none;transition:all .2s;cursor:pointer;background:transparent;
}
.filter-chip:hover,.filter-chip.active{
    background:#fff;color:var(--green);border-color:#fff;
}

/* ── Body area ───────────────────────────────────────────── */
.tlist-body{
    max-width:1280px;margin:0 auto;
    padding:3rem 1.5rem 4rem;
}

/* ── Teacher Card ────────────────────────────────────────── */
.teacher-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    gap:1.5rem;
}

.t-prof-card{
    background:#fff;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 2px 16px rgba(26,107,60,.07);
    border:1.5px solid #F0EDE6;
    transition:transform .25s,box-shadow .25s;
    display:flex;flex-direction:column;
}
.t-prof-card:hover{
    transform:translateY(-4px);
    box-shadow:0 8px 32px rgba(26,107,60,.13);
}

.t-prof-top{
    position:relative;
    padding:1.5rem 1.5rem 1rem;
    background:linear-gradient(135deg,var(--creamd) 0%,#fff 100%);
    text-align:center;
}
.t-prof-top::before{
    content:'';position:absolute;bottom:0;left:0;right:0;height:1px;background:#EDE9E0;
}
.t-avatar{
    width:80px;height:80px;border-radius:50%;
    object-fit:cover;
    border:3px solid #fff;
    box-shadow:0 2px 12px rgba(26,107,60,.15);
    margin:0 auto .75rem;display:block;
}
.t-name{font-size:1rem;font-weight:700;color:var(--text);margin-bottom:.2rem;}
.t-spec{font-size:.8rem;color:var(--green);font-weight:600;}

/* stars */
.t-stars{display:flex;align-items:center;justify-content:center;gap:.2rem;margin:.5rem 0;}
.t-stars i{font-size:.65rem;color:#D1D5DB;}
.t-stars i.lit{color:#F59E0B;}
.t-stars span{font-size:.75rem;color:var(--muted);margin-left:.3rem;}

/* Tags */
.t-tags{display:flex;flex-wrap:wrap;gap:.35rem;justify-content:center;padding:.1rem 0 .75rem;}
.t-tag{
    font-size:.7rem;font-weight:600;
    padding:.2rem .6rem;border-radius:99px;
    background:rgba(26,107,60,.08);color:var(--green);
}

/* Body */
.t-prof-body{padding:1rem 1.5rem 1.25rem;flex:1;display:flex;flex-direction:column;gap:.6rem;}
.t-meta{display:flex;align-items:center;gap:1rem;flex-wrap:wrap;}
.t-meta-item{font-size:.8rem;color:var(--muted);}
.t-meta-item i{color:var(--green);margin-right:.25rem;font-size:.7rem;}

.t-rate{
    display:flex;align-items:baseline;gap:.2rem;margin-top:auto;
}
.t-rate-num{font-size:1.2rem;font-weight:800;color:var(--text);}
.t-rate-unit{font-size:.75rem;color:var(--muted);}

.t-prof-foot{
    padding:.75rem 1.25rem;
    border-top:1px solid #EDE9E0;
    display:flex;gap:.5rem;
}
.t-btn-main{
    flex:1;text-align:center;padding:.55rem;border-radius:10px;
    background:var(--green);color:#fff;
    font-size:.8rem;font-weight:700;text-decoration:none;
    transition:background .2s;
}
.t-btn-main:hover{background:var(--gl);}
.t-btn-ghost{
    padding:.55rem .75rem;border-radius:10px;
    border:1.5px solid #E5E7EB;color:var(--muted);
    font-size:.8rem;font-weight:600;text-decoration:none;
    transition:border-color .2s,color .2s;
}
.t-btn-ghost:hover{border-color:var(--green);color:var(--green);}

/* Empty state */
.empty-state{
    text-align:center;padding:5rem 1rem;
    grid-column:1/-1;
}
.empty-state i{font-size:2.5rem;color:#D1D5DB;display:block;margin-bottom:1rem;}
.empty-state p{color:var(--muted);}

/* Pagination */
.pag-wrap{margin-top:2.5rem;display:flex;justify-content:center;}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="tlist-hero">
    <h1>Meet Our <span>Certified Teachers</span></h1>
    <p>Every teacher is verified, experienced, and dedicated to your Quranic journey.</p>

    <div class="filter-bar">
        <a href="<?php echo e(route('teachers')); ?>" class="filter-chip <?php echo e(!request('subject') ? 'active' : ''); ?>">All Teachers</a>
        <?php $__currentLoopData = ['Tajweed','Hifz','Noorani Qaida','Nazrah','Dars-e-Nizami']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('teachers', ['subject' => $sub])); ?>"
               class="filter-chip <?php echo e(request('subject') === $sub ? 'active' : ''); ?>"><?php echo e($sub); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div class="tlist-body">

    <div class="teacher-grid">
        <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="t-prof-card reveal">
                <div class="t-prof-top">
                    <img src="<?php echo e($teacher->user->avatar_url); ?>"
                         class="t-avatar"
                         alt="<?php echo e($teacher->user->name); ?>">
                    <div class="t-name"><?php echo e($teacher->user->name); ?></div>
                    <div class="t-spec"><?php echo e($teacher->specialization ?? 'Quran Teacher'); ?></div>

                    <div class="t-stars">
                        <?php for($i=1;$i<=5;$i++): ?>
                            <i class="fas fa-star <?php echo e($i <= $teacher->rating ? 'lit' : ''); ?>"></i>
                        <?php endfor; ?>
                        <span><?php echo e(number_format($teacher->rating,1)); ?> (<?php echo e($teacher->total_reviews); ?>)</span>
                    </div>

                    <?php if($teacher->subjects): ?>
                        <div class="t-tags">
                            <?php $__currentLoopData = array_slice($teacher->subjects, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="t-tag"><?php echo e($subject); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="t-prof-body">
                    <div class="t-meta">
                        <span class="t-meta-item">
                            <i class="fas fa-briefcase"></i><?php echo e($teacher->experience_years); ?> yrs exp.
                        </span>
                        <?php if($teacher->nationality): ?>
                            <span class="t-meta-item">
                                <i class="fas fa-globe"></i><?php echo e($teacher->nationality); ?>

                            </span>
                        <?php endif; ?>
                        <span class="t-meta-item">
                            <i class="fas fa-video"></i><?php echo e($teacher->total_sessions); ?> sessions
                        </span>
                    </div>

                    <div class="t-rate">
                        <span class="t-rate-num">$<?php echo e(number_format($teacher->hourly_rate, 0)); ?></span>
                        <span class="t-rate-unit">/ hour</span>
                    </div>
                </div>

                <div class="t-prof-foot">
                    <a href="<?php echo e(route('teachers.show', $teacher)); ?>" class="t-btn-main">
                        View Profile
                    </a>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->isStudent()): ?>
                            <a href="<?php echo e(route('student.bookings.create', ['teacher_id' => $teacher->id])); ?>" class="t-btn-ghost" title="Book session">
                                <i class="fas fa-calendar-plus"></i>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo e(route('register', 'student')); ?>" class="t-btn-ghost" title="Book session">
                            <i class="fas fa-calendar-plus"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <p>No teachers found. Please check back soon.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="pag-wrap"><?php echo e($teachers->links()); ?></div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Peace Institute\Website\resources\views/teachers/index.blade.php ENDPATH**/ ?>