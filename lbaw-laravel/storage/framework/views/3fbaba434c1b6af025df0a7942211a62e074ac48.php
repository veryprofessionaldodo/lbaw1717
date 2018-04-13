<?php $__env->startSection('content'); ?>
<form method="POST" action="<?php echo e(route('login')); ?>">
    <?php echo e(csrf_field()); ?>


    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
    <?php if($errors->has('email')): ?>
        <span class="error">
          <?php echo e($errors->first('email')); ?>

        </span>
    <?php endif; ?>

    <label for="password" >Password</label>
    <input id="password" type="password" name="password" required>
    <?php if($errors->has('password')): ?>
        <span class="error">
            <?php echo e($errors->first('password')); ?>

        </span>
    <?php endif; ?>

    <label>
        <input type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>> Remember Me
    </label>

    <button type="submit">
        Login
    </button>
    <a class="button button-outline" href="<?php echo e(route('register')); ?>">Register</a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>