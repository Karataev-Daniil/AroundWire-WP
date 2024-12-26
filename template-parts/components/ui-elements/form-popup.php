<div id="form-popup" class="popup-overlay">
    <div class="popup-content">
        <span id="close-popup" class="close-button"></span>
        <?php
        // Retrieve the 'form_type' argument or set a default value
        $form_type = isset($form_type) ? $form_type : 'default';

        // Pass the 'form_type' argument to the 'form-messeng' template
        load_template_with_args('template-parts/components/ui-elements/form-messeng', [
            'form_type' => $form_type
        ]);
        ?>
    </div>
</div>
