
        <!-- Terms and Conditions Modal -->
        <div class="modal fade" id="terms-conditions" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header bg-light border-bottom-0">
                        <h5 class="modal-title">Terms and Conditions & Privacy Policies</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Please review and accept our terms to continue.</p>
                        <form id="terms-and-conditions-form">
                            <input type="hidden" name="terms_and_conditions" value="0">
                            <input type="checkbox" name="terms_and_conditions" value="1" id="terms_checkbox">
                            <label for="terms_checkbox">I agree to the Terms and Conditions</label>
                            <button type="submit" class="btn btn-primary">Accept</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Change Modal -->
        <div class="modal fade" id="password-change-modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header text-white">
                        <h5 class="modal-title">🔑 Change Password</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-users-change-password" method="post">
                            @csrf
                            <!-- Old Password -->
                            <div class="mb-3">
                                <label for="oldpassword" class="form-label">Old Password {!! dynamicRedAsterisk() !!}</label>
                                <div class="input-group input-group-merge position-relative">
                                    {!! Form::password('oldpassword', [
                                        'placeholder' => 'Enter Old Password',
                                        'id' => 'oldpassword',
                                        'class' => 'form-control rounded-pill shadow-sm',
                                    ]) !!}
                                    <span class="input-group-text bg-white rounded-end cursor-pointer"
                                        id="toggle-old-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Ensure your old password is correct.</small>
                            </div>
                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password {!! dynamicRedAsterisk() !!}</label>
                                <div class="input-group input-group-merge position-relative">
                                    {!! Form::password('password', [
                                        'placeholder' => 'Enter New Password',
                                        'id' => 'password',
                                        'class' => 'form-control rounded-pill shadow-sm',
                                    ]) !!}
                                    <span class="input-group-text bg-white rounded-end cursor-pointer"
                                        id="toggle-new-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Use at least 8 characters with a mix of letters,
                                    numbers,
                                    and symbols.</small>
                            </div>
                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password
                                    {!! dynamicRedAsterisk() !!}</label>
                                <div class="input-group input-group-merge position-relative">
                                    {!! Form::password('confirm-password', [
                                        'placeholder' => 'Confirm New Password',
                                        'id' => 'confirm_password',
                                        'class' => 'form-control rounded-pill shadow-sm',
                                    ]) !!}
                                    <span class="input-group-text bg-white rounded-end cursor-pointer"
                                        id="toggle-confirm-password">
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                                <small class="form-text text-muted">Make sure the passwords match.</small>
                            </div>
                            <!-- Submit Button -->
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" id="submit" class="btn btn-primary btn-lg shadow-sm rounded-pill">
                                    <i class="bx bx-save"></i> Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device Verification Modal -->
        <div class="modal fade" id="verificationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h3>Verify This Device!!</h3>
                        <p>
                            Please confirm that you want to verify this device to ensure proper functionality and data
                            visualization.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activate Device Modal -->
        <div class="modal fade" id="activateDeviceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mx-auto">Device Not Authorized Yet!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p>Please authorize your device to continue.</p>
                        <button type="button" class="btn btn-primary">Authorize Now</button>
                    </div>
                </div>
            </div>
        </div>
