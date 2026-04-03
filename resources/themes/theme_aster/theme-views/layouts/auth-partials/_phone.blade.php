<div class="form-group mb-3">
    <label for="firebase-phone-number">{{ translate('phone') }}</label>
    <input
        type="tel" name="phone"
        id="firebase-phone-number"
        value="{{old('phone')}}"
        class="form-control phone-input-with-country-picker-login"
        placeholder="{{ translate('enter_phone_number') }}"
    />
</div>
