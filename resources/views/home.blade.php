<x-layout doctitle="Welcome">
    <div class="container py-md-5">
      <div class="row align-items-center">
        <div class="col-lg-7 py-3 py-md-5">
          <h1 class="display-3 font-weight-bold mb-3">Remember Writing?</h1>
          <p class="lead text-muted">Are you sick of short tweets and impersonal &ldquo;shared&rdquo; posts that are reminiscent of the late 90&rsquo;s email forwards? We believe getting back to actually writing is the key to enjoying the internet again.</p>
          <div class="mt-4">
            <p class="text-muted">
              <i class="fas fa-check text-success mr-2"></i> Real conversations
            </p>
            <p class="text-muted">
              <i class="fas fa-check text-success mr-2"></i> Follow your interests
            </p>
            <p class="text-muted">
              <i class="fas fa-check text-success mr-2"></i> Connect with others
            </p>
          </div>
        </div>
        <div class="col-lg-5 pl-lg-5 pb-3 py-lg-5">
          <div class="card shadow-lg border-0">
            <div class="card-body p-4">
              <h3 class="mb-4 text-center"><i class="fas fa-user-plus text-danger mr-2"></i> Join FirstApp</h3>
              <form action="/register" method="POST" id="registration-form">
                @csrf
                <div class="form-group">
                  <label for="username-register" class="text-muted mb-2 font-weight-bold"><small>Username</small></label>
                  <input value="{{old('username')}}" name="username" id="username-register" class="form-control" type="text" placeholder="Choose a username" autocomplete="off" />
                  @error('username')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="email-register" class="text-muted mb-2 font-weight-bold"><small>Email</small></label>
                  <input value="{{old('email')}}" name="email" id="email-register" class="form-control" type="email" placeholder="you@example.com" autocomplete="off" />
                  @error('email')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="password-register" class="text-muted mb-2 font-weight-bold"><small>Password</small></label>
                  <input name="password" id="password-register" class="form-control" type="password" placeholder="At least 8 characters" />
                  @error('password')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                  @enderror
                </div>

                <div class="form-group">
                  <label for="password-register-confirm" class="text-muted mb-2 font-weight-bold"><small>Confirm Password</small></label>
                  <input name="password_confirmation" id="password-register-confirm" class="form-control" type="password" placeholder="Confirm your password" />
                  @error('password_confirmation')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                  @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block mt-4 btn-strong">
                  <i class="fas fa-user-plus mr-2"></i> Create Account
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</x-layout>