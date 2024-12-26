jQuery(document).ready(function ($) {
    const $templateSelect = $('[name="acf[field_template_presets]"]');
    const $versionFields = $('[name^="acf[field_"]');

    function toggleLoading(state) {
        if (state) {
            $('body').addClass('loading');
        } else {
            $('body').removeClass('loading');
        }
    }

    function sendAjaxRequest(action, data, onSuccess, onError) {
        toggleLoading(true);
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: { action, ...data },
            success(response) {
                toggleLoading(false);
                if (response.success) {
                    onSuccess && onSuccess(response);
                } else {
                    alert(response.data || `Error performing ${action}.`);
                }
            },
            error() {
                toggleLoading(false);
                onError && onError();
                alert(`An error occurred while performing ${action}.`);
            },
        });
    }

    function updateFields(templateData) {
        $versionFields.each(function () {
            const fieldKey = $(this).attr('name').match(/acf\[(field_\w+)\]/);
            if (fieldKey && fieldKey[1]) {
                const fieldName = fieldKey[1];
                if (templateData[fieldName] !== undefined) {
                    $(this).val(templateData[fieldName]).trigger('input').trigger('change');
                }
            } else {
                console.error('Field key not found or invalid.');
            }
        });
    }

    function updateTemplateSelect() {
        sendAjaxRequest('get_saved_templates', {}, (response) => {
            const templates = response.data;
            $templateSelect.empty().append('<option value="">Select a template</option>');
            templates.forEach((template) => {
                $templateSelect.append(`<option value="${template}">${template}</option>`);
            });
        });
    }

    $templateSelect.on('change', function () {
        const selectedTemplate = $(this).val();
        if (!selectedTemplate) {
            alert('Please select a valid template.');
            return;
        }

        sendAjaxRequest(
            'get_template_settings',
            { template_name: selectedTemplate },
            (response) => {
                updateFields(response.data.block_versions);
            }
        );
    });

    $('#save-template').on('click', function () {
        const templateName = prompt("Please enter the template name:");
        if (!templateName || templateName.trim() === "") {
            alert('Template name is required!');
            return;
        }

        const blockVersions = {};
        $versionFields.each(function () {
            const fieldKey = $(this).attr('name').match(/acf\[(field_\w+)\]/)[1];
            blockVersions[fieldKey] = $(this).val();
        });

        sendAjaxRequest(
            'save_block_template',
            { template_name: templateName, block_versions: blockVersions },
            () => {
                alert('Template saved successfully!');
                updateTemplateSelect();
            }
        );
    });

    $('#delete-template').on('click', function () {
        const selectedTemplate = $templateSelect.val();
        if (!selectedTemplate) {
            alert('Please select a template to delete.');
            return;
        }

        if (!confirm('Are you sure you want to delete this template?')) {
            return;
        }

        sendAjaxRequest(
            'delete_template',
            { template_name: selectedTemplate },
            () => {
                alert('Template deleted successfully!');
                updateTemplateSelect();
            }
        );
    });

    updateTemplateSelect();
});