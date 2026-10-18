<div class="admin-form-grid">
    <label>Full name<input type="text" name="name" value="{{ old('name', $customer->name ?? '') }}" maxlength="120" required></label>
    <label>Email address <small>Optional</small><input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" maxlength="190" autocomplete="email"></label>
    <x-phone-input variant="admin" :value="$customer->phone ?? ''" />
    <label class="admin-form-wide">Private notes <small>Only visible in the admin panel</small><textarea name="notes" rows="6" maxlength="5000" placeholder="Preferences, follow-up notes or other useful information">{{ old('notes', $customer->notes ?? '') }}</textarea></label>
</div>
