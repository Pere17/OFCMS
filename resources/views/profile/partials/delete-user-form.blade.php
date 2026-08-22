<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
    Delete Account
</button>

<div class="modal fade @if($errors->userDeletion->isNotEmpty()) show d-block @endif" id="confirmUserDeletion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body p-4">
                    <h5 class="fw-semibold">Are you sure you want to delete your account?</h5>
                    <p class="text-muted small">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Please enter your password to confirm.
                    </p>

                    <input id="password" name="password" type="password" placeholder="Password"
                           class="form-control @error('password', 'userDeletion') is-invalid @enderror">
                    @error('password', 'userDeletion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
