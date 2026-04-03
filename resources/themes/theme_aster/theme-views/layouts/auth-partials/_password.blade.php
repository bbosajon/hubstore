@php($randomLabelId=rand(1111, 9999))
<div class="mb-4">
    <label for="password-{{ $randomLabelId }}">
        {{ translate('password') }}
    </label>
    <div class="input-inner-end-ele">
        <input name="password" type="password" id="password-{{ $randomLabelId }}"
               class="form-control auth-password-input"
               placeholder="{{ translate('ex:').':'.'6+'.' '.translate('character') }}" required/>
        <i class="bi bi-eye-slash-fill togglePassword"></i>
    </div>
</div>
