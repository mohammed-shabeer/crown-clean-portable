<section class="auth bg-base d-flex flex-wrap">
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <img src="{{asset('assets/images/login-bg.jpg')}}" class="tw-h-full object-fit-cover tw-w-full" alt="">
        </div>
    </div>
    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-464-px mx-auto w-100">
            <div>
                <div class="tw-w-full tw-flex tw-items-center tw-justify-center">
                    <a href="#" class="tw-mb-8 max-w-290-px ">
                        <img src="{{asset('assets/images/logo-ct.png')}}" alt="" class="tw-max-h-24 tw-object-contain">
                    </a>
                </div>
                <h4 class="mb-12">Activate Application</h4>
                <p class="mb-32 text-secondary-light text-lg">This copy must be activated on this system.</p>
            </div>

            @if($activated)
                <div class="alert alert-success">Already activated. <a href="/">Continue</a></div>
            @else
                <form wire:submit.prevent="activate">
                    <div class="mb-24">
                        <label class="form-label">System ID</label>
                        <div class="d-flex gap-8">
                            <input type="text" class="form-control h-56-px bg-neutral-50 radius-12" value="{{ $machine_id }}" readonly onclick="this.select()">
                            <button type="button" class="btn btn-primary radius-12 px-16" onclick="navigator.clipboard.writeText('{{ $machine_id }}').then(()=>this.textContent='Copied!')">Copy</button>
                        </div>
                        <p class="text-secondary-light mt-8">Send this System ID to your vendor to receive an activation code.</p>
                    </div>

                    <div class="mb-24">
                        <label class="form-label">Activation Code</label>
                        <textarea wire:model="activation_code" class="form-control bg-neutral-50 radius-12" rows="3" placeholder="Paste the activation code here"></textarea>
                        @error('activation_code') <span class="text-danger">{{$message}}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 radius-12">Activate</button>
                </form>
            @endif
        </div>
    </div>
</section>
