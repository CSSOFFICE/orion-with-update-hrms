<!--flash messages-->
<?php if(Session::has('success-notification')): ?>
<span id="js-trigger-session-message" data-type="success"
    data-message="<?php echo e(Session::get('success-notification')); ?>"></span>
<?php endif; ?>
<?php if(Session::has('error-notification')): ?>
<span id="js-trigger-session-message" data-type="warning"
    data-message="<?php echo e(Session::get('error-notification')); ?>"></span>
<?php endif; ?>

<!--flash messages longer duration-->
<?php if(Session::has('success-notification-longer')): ?>
<span id="js-trigger-session-message" data-type="success"
    data-message="<?php echo e(Session::get('success-notification-longer')); ?>"></span>
<?php endif; ?>

<?php if(Session::has('error-notification-longer')): ?>
<span id="js-trigger-session-message" data-type="warning"
    data-message="<?php echo e(Session::get('error-notification-longer')); ?>"></span>
<?php endif; ?>

<!--force user password change-->
<?php if(Auth::user() && Auth::user()->force_password_change == 'yes'): ?>
<span id="js-trigger-force-password-change" class="hidden edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
    data-toggle="modal" data-target="#commonModal" data-url="<?php echo e(url('user/updatepassword')); ?>"
    data-loading-target="commonModalBody" data-action-url="<?php echo e(url('user/updatepassword')); ?>" data-action-method="PUT"
    data-action-ajax-class="" data-modal-size="modal-sm" data-form-design="form-material"
    data-header-visibility="hidden" data-close-button-visibility="hidden"
    data-action-ajax-loading-target="commonModalBody"></span>
<?php endif; ?>

<!--polling - general data [only when debug mode is disabled, else it resets the debug toolbar]-->






<!--dynamic load - a expense-->
<?php if(config('visibility.dynamic_load_modal')): ?>
<span class="hidden" id="js-trigger-dynamic-modal" data-payload="<?php echo e(config('settings.dynamic_trigger_dom')); ?>"></span>
<?php endif; ?>

<?php /**PATH E:\xampp\htdocs\css\orion\application\resources\views/layout/automationjs.blade.php ENDPATH**/ ?>