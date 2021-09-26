@extends('layouts.app')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" >
            <div class="card" style=" width:450px; height:450px; margin:auto; box-shadow: 5px 5px 5px #69BE28">
                <div class="card-header" style="background-color: #69BE28; color:white"><strong>{{ __('Login') }}</strong></div>

                <div class="card-body" style="display:flex; justify-content: center; align-items: center; " >
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" ><strong>{{ __('Endereço de E-mail') }}</strong></label>
                            <label  class="col-md-4 col-form-label text-md-right"></label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  required autocomplete="email" autofocus placeholder="Digite seu e-mail">
                             @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror    
                        </div>
                        
                        <div class="form-group row" style="padding-top:15px;">
                            <label for="password"><strong>{{ __('Senha') }}</strong></label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Digite sua senha">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group row" style="padding-top:2px;">
                            
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Salvar senha') }}
                                    </label>
                                </div>
                            
                        </div>

                        <div class="row" style="margin-top:30px">                           
                                <button type="submit" class="btn btn-primary" style="width: 200px; margin-left:auto; margin-right:auto; background-color:#69BE28;">
                                    {{ __('Login') }}
                                </button>
                                
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('register') }}" style="margin-top:30px">
                                        <strong>{{ __('Não possui cadastro? Cadastre-se.') }}</strong>                                          
                                    </a>
                                @endif                              
                                
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
