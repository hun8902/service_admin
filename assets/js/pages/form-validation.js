'use strict';
$(document).ready(function() {
    $(function() {
        $.validator.addMethod('phone_format', function(value, element) {
            return this.optional(element) || /^\(\d{3}\)[ ]\d{3}\-\d{4}$/.test(value);
        }, 'Invalid phone number.');
        $('#student_form').validate({
            ignore: '.ignore, .select2-input',
            focusInvalid: false,
            rules: {
                'validation-email': {
                    required: true,
                    email: true
                },
                'validation-password': {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                'validation-password-confirmation': {
                    required: true,
                    minlength: 6,
                    equalTo: 'input[name="validation-password"]'
                },
                'ac_id': {
                    required: true
                },
                'validation-url': {
                    required: true,
                    url: true
                },
                'validation-phone': {
                    required: true,
                    phone_format: true
                },
                'validation-select': {
                    required: true
                },
                'validation-bs-tagsinput': {
                    required: true
                },
                'validatignoreion-text': {
                    required: true
                },
                'validation-file': {
                    required: true
                },
                'validation-switcher': {
                    required: true
                },
                'validation-radios': {
                    required: true
                },
                'validation-radios-custom': {
                    required: true
                },
                'validation-checkbox': {
                    required: true
                },
                'validation-checkbox-custom': {
                    required: true
                },
            },
            errorPlacement: function errorPlacement(error, element) {
                var $parent = $(element).parents('.form-group');
                if ($parent.find('.jquery-validation-error').length) {
                    return;
                }
                $parent.append(error.addClass('jquery-validation-error small form-text invalid-feedback'));
            },
            highlight: function(element) {
                var $el = $(element);
                var $parent = $el.parents('.form-group');
                $el.addClass('is-invalid');
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            }
        });
    });
});