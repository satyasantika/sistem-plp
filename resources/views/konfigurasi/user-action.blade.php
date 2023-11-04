<div class="modal-content">
    <form id="formAction" action="{{ $user->id ? route('users.update',$user->id) : route('users.store') }}" method="post">
        @csrf
        @if ($user->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $user->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                {{-- nama lengkap --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}" name="name" class="form-control" id="name" required autofocus>
                    </div>
                </div>
                {{-- username --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" value="{{ $user->username }}" name="username" class="form-control" id="username" required>
                    </div>
                </div>
                {{-- email --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat email</label>
                        <input type="email" value="{{ $user->email }}" name="email" class="form-control" id="email" required>
                    </div>
                </div>
                {{-- password --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Buat Password</label>
                        <input type="password" value="{{ $user->password }}" name="password" class="form-control" id="password" required {{ $user->id ? 'readonly' : '' }}>
                    </div>
                </div>
                {{-- role --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" class="form-control @error('role') is-invalid @enderror" name="role" required {{ $user->id ? 'disabled' : '' }}>
                            @if ($user->id)
                            <option value="">{{ $user->getRoleNames()->implode(', ') }}</option>
                            @else
                            <option value="">-- Tanpa Role --</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected($role == $user->getRoleNames()->implode(', '))>{{ Str::ucfirst($role) }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                {{-- mapel --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Pilih Mapel</label>
                        <select id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" name="subject_id">
                            <option value="">-- Tanpa Mapel --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" @selected($subject->id == $user->subject_id)>{{ Str::ucfirst($subject->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- tempat lahir --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="birth_place" class="form-label">Tempat Lahir</label>
                        <input type="text" value="{{ $user->birth_place }}" name="birth_place" class="form-control" id="birth_place">
                    </div>
                </div>
                {{-- tanggal lahir --}}
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                        <input type="date" value="{{ $user->birth_date ? $user->birth_date->format('Y-m-d') : date('Y-m-d') }}" name="birth_date" class="form-control" id="birth_date">
                    </div>
                </div>
                {{-- jenis kelamin --}}
                <div class="col-md-3">
                    <label for="gender" class="form-label">Gender</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="genderL" value="L" {{ $user->gender == 'L' ? 'checked' : '' }}>
                        <label class="form-check-label" for="genderL">Laki-laki</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" id="genderP" value="P" {{ $user->gender == 'P' ? 'checked' : '' }}>
                        <label class="form-check-label" for="genderP">Perempuan</label>
                    </div>
                </div>
                {{-- alamat --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat tinggal saat ini</label>
                        <textarea name="address" class="form-control" id="address">{{ $user->address }}</textarea>
                    </div>
                </div>
                {{-- no WA --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">no. WA aktif</label>
                        <input type="text" value="{{ $user->phone }}" name="phone" class="form-control" id="phone">
                    </div>
                </div>
                {{-- provider --}}
                {{-- <div class="col-md-6">
                    <div class="mb-3">
                        <label for="provider" class="form-label">Provider</label>
                        <select id="provider" class="form-control @error('provider') is-invalid @enderror" name="provider">
                            <option value="">-- Pilih Provider --</option>
                            @foreach ($providers as $provider)
                                <option value="{{ $provider }}" {{ $user->provider == $provider ? 'selected' : '' }}>{{ $provider }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}
                {{-- status pns --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="is_pns" class="form-label">Status PNS</label>
                        <select id="is_pns" class="form-control @error('is_pns') is-invalid @enderror" name="is_pns">
                            @foreach ($is_pns as $key => $value)
                                <option value="{{ $key }}" @selected($user->is_pns == $key)>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- golongan --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="golongan" class="form-label">Golongan Kepegawaian</label>
                        <select id="golongan" class="form-control @error('golongan') is-invalid @enderror" name="golongan">
                            <option value="">-- Pilih Golongan --</option>
                            @foreach ($golongans as $golongan)
                                <option value="{{ $golongan }}" @selected($user->golongan == $golongan)>Golongan {{ $golongan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- npwp --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="npwp" class="form-label">npwp</label>
                        <input type="text" value="{{ $user->npwp }}" name="npwp" class="form-control" id="npwp">
                    </div>
                </div>
                {{-- nomor_rekening --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nomor_rekening" class="form-label">nomor_rekening</label>
                        <input type="text" value="{{ $user->nomor_rekening }}" name="nomor_rekening" class="form-control" id="nomor_rekening">
                    </div>
                </div>
                {{-- bank --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="bank" class="form-label">bank</label>
                        <select id="bank" class="form-control @error('bank') is-invalid @enderror" name="bank">
                            <option value="">-- Pilih Bank untuk pencairan honor --</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank }}" @selected($user->bank == $bank)>bank {{ $bank }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>
