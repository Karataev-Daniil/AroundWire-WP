<?php
$form_type = isset($form_type) ? $form_type : 'default';

?>
<div class="form">
    <?php 
    if ($form_type === 'home_form') {
        echo do_shortcode('[contact-form-7 id="160e85a" title="homepage__contact-form"]');
    } else {
        echo do_shortcode('[contact-form-7 id="6033b00" title="lending__contact-form"]');
    }
    ?>
</div>
