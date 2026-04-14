<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const tab = ref('login');

const loginForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const registerForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    codigo_registro: '',
});

function submit() {
    loginForm.post(route('login'), {
        onFinish: () => loginForm.reset('password'),
    });
}

function register() {
    registerForm.post(route('register'), {
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
    });
}

const carPairs = [
    ['/Images/Clasico1.png', '/Images/Clasico2.png'],
    ['/Images/Clasico3.png', '/Images/Clasico4.png'],
];
const carIndex = ref(0);
setInterval(() => { carIndex.value = (carIndex.value + 1) % 2; }, 3000);

const showLoginPass    = ref(false);
const showRegisterPass = ref(false);
const showConfirmPass  = ref(false);
</script>

<template>
    <Head title="Piston Drive — Iniciar sesión" />

    <div class="root">

        <!-- ══ IZQUIERDA — 2 fotos por slide ══ -->
        <div class="left">
            <div
                v-for="(pair, i) in carPairs"
                :key="i"
                class="car-pair"
                :class="{ 'car-pair-active': carIndex === i }"
            >
                <img :src="pair[0]" alt="Auto clásico" class="car-img" />
                <img :src="pair[1]" alt="Auto clásico" class="car-img" />
            </div>
            <div class="car-overlay" />
            <div class="car-caption">
                <span class="car-caption-text">Piston Drive · Taller Mecánico</span>
            </div>
            <div class="car-dots">
                <button
                    v-for="i in 2"
                    :key="'d'+i"
                    class="car-dot"
                    :class="{ 'car-dot-active': carIndex === i-1 }"
                    @click="carIndex = i-1"
                />
            </div>
        </div>

        <!-- ══ DERECHA — oscura con el form ══ -->
        <div class="right">
            <div class="right-pattern" />
            <div class="right-accent-top" />

            <div class="right-inner">

               

                <!-- Tabs -->
                <div class="tabs">
                    <button class="tab" :class="{ active: tab === 'login' }" @click="tab = 'login'">
                        Iniciar sesión
                    </button>
                    <button class="tab" :class="{ active: tab === 'register' }" @click="tab = 'register'">
                        Crear cuenta
                    </button>
                    <div class="tab-track" :class="tab === 'register' ? 'track-right' : 'track-left'" />
                </div>

                <!-- LOGIN -->
                <div v-show="tab === 'login'" class="form">
                    <div class="form-head">
                        <h1 class="form-title">Bienvenido</h1>
                        <p class="form-sub">Ingresa tus credenciales para continuar</p>
                    </div>

                    <div v-if="status" class="msg-success">{{ status }}</div>

                    <div class="field">
                        <label class="flabel">Correo electrónico</label>
                        <div class="finput" :class="{ ferror: loginForm.errors.email }">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.5"/><path d="M22 6l-10 7L2 6" stroke="currentColor" stroke-width="1.5"/></svg>
                            <input v-model="loginForm.email" type="email" placeholder="tu@correo.com" autofocus @keyup.enter="submit" />
                        </div>
                        <span v-if="loginForm.errors.email" class="ferr-msg">{{ loginForm.errors.email }}</span>
                    </div>

                    <div class="field">
                        <label class="flabel">Contraseña</label>
                        <div class="finput" :class="{ ferror: loginForm.errors.password }">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="1.5"/></svg>
                            <input v-model="loginForm.password" :type="showLoginPass ? 'text' : 'password'" placeholder="••••••••" @keyup.enter="submit" />
                            <button type="button" class="eye" @click="showLoginPass = !showLoginPass">
                                <svg v-if="!showLoginPass" width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                                <svg v-else width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" stroke="currentColor" stroke-width="1.5"/><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.5"/></svg>
                            </button>
                        </div>
                        <span v-if="loginForm.errors.password" class="ferr-msg">{{ loginForm.errors.password }}</span>
                    </div>

                    <div class="remember">
                        <label>
                            <input v-model="loginForm.remember" type="checkbox" class="chk" />
                            <span>Recordarme</span>
                        </label>
                    </div>

                    <button class="btn-primary" :disabled="loginForm.processing" @click="submit">
                        <span v-if="loginForm.processing" class="spinner" />
                        <span v-else>Iniciar sesión</span>
                    </button>
                </div>

                <!-- REGISTRO -->
                <div v-show="tab === 'register'" class="form">
                    <div class="form-head">
                        <h1 class="form-title">Crear cuenta</h1>
                        <p class="form-sub">Solo personal autorizado</p>
                    </div>

                    <div class="field">
                        <label class="flabel">Nombre completo</label>
                        <div class="finput" :class="{ ferror: registerForm.errors.name }">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <input v-model="registerForm.name" type="text" placeholder="Tu nombre completo" />
                        </div>
                        <span v-if="registerForm.errors.name" class="ferr-msg">{{ registerForm.errors.name }}</span>
                    </div>

                    <div class="field">
                        <label class="flabel">Correo electrónico</label>
                        <div class="finput" :class="{ ferror: registerForm.errors.email }">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.5"/><path d="M22 6l-10 7L2 6" stroke="currentColor" stroke-width="1.5"/></svg>
                            <input v-model="registerForm.email" type="email" placeholder="tu@correo.com" />
                        </div>
                        <span v-if="registerForm.errors.email" class="ferr-msg">{{ registerForm.errors.email }}</span>
                    </div>

                    <div class="field">
                        <label class="flabel">Contraseña</label>
                        <div class="finput" :class="{ ferror: registerForm.errors.password }">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="1.5"/></svg>
                            <input v-model="registerForm.password" :type="showRegisterPass ? 'text' : 'password'" placeholder="Mínimo 8 caracteres" />
                            <button type="button" class="eye" @click="showRegisterPass = !showRegisterPass">
                                <svg v-if="!showRegisterPass" width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                                <svg v-else width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" stroke="currentColor" stroke-width="1.5"/><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.5"/></svg>
                            </button>
                        </div>
                        <span v-if="registerForm.errors.password" class="ferr-msg">{{ registerForm.errors.password }}</span>
                    </div>

                    <div class="field">
                        <label class="flabel">Confirmar contraseña</label>
                        <div class="finput">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><rect x="3" y="11" width="18" height="11" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="1.5"/><path d="M9 16l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <input v-model="registerForm.password_confirmation" :type="showConfirmPass ? 'text' : 'password'" placeholder="Repite tu contraseña" />
                            <button type="button" class="eye" @click="showConfirmPass = !showConfirmPass">
                                <svg v-if="!showConfirmPass" width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="1.5"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.5"/></svg>
                                <svg v-else width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" stroke="currentColor" stroke-width="1.5"/><line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.5"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="field">
                        <label class="flabel">Código de registro</label>
                        <div class="finput" :class="{ ferror: registerForm.errors.codigo_registro }">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <input v-model="registerForm.codigo_registro" type="text" placeholder="Código del administrador" @keyup.enter="register" />
                        </div>
                        <span v-if="registerForm.errors.codigo_registro" class="ferr-msg">{{ registerForm.errors.codigo_registro }}</span>
                        <span class="fhint">Solicita el código al administrador del taller</span>
                    </div>

                    <button class="btn-primary" :disabled="registerForm.processing" @click="register">
                        <span v-if="registerForm.processing" class="spinner" />
                        <span v-else>Crear cuenta de secretaria</span>
                    </button>
                </div>

                <p class="footer">© 2026 Piston Drive · Taller Mecánico</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

.root {
    display: flex;
    height: 100vh;
    max-height: 100vh;
    overflow: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

/* ══ IZQUIERDA — carrusel ══ */
.left {
    flex: 1;
    position: relative;
    overflow: hidden;
    height: 100vh;
    background: #111418;
}

.car-pair {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    display: flex;
    gap: 4px;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.car-pair-active {
    opacity: 1;
}

.car-img {
    flex: 1;
    width: 50%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    background: #111418;
}

.car-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to right,
        rgba(0,0,0,0.2) 0%,
        rgba(0,0,0,0.05) 50%,
        rgba(26,32,44,0.55) 100%
    );
    pointer-events: none;
    z-index: 1;
}

.car-caption {
    position: absolute;
    bottom: 60px;
    left: 0;
    right: 0;
    text-align: center;
    z-index: 2;
}

.car-caption-text {
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: rgba(255,255,255,0.5);
}

.car-dots {
    position: absolute;
    bottom: 28px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    z-index: 2;
}

.car-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.5);
    background: transparent;
    cursor: pointer;
    padding: 0;
    transition: all 0.25s;
}

.car-dot-active {
    background: white;
    border-color: white;
    width: 28px;
    border-radius: 4px;
}

/* ══ DERECHA — oscura ══ */
.right {
    width: 500px;
    min-width: 500px;
    background: #1A202C;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 48px 44px;
    height: 100vh;
}

.right-pattern {
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 80%, rgba(192,25,42,0.12) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(192,25,42,0.08) 0%, transparent 40%),
        repeating-linear-gradient(
            45deg,
            transparent,
            transparent 20px,
            rgba(255,255,255,0.012) 20px,
            rgba(255,255,255,0.012) 21px
        );
    pointer-events: none;
}

.right-accent-top {
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(to right, transparent, #C0192A 30%, #9B1422 70%, transparent);
}

.right-inner {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 380px;
}

.mobile-logo { display: none; justify-content: center; margin-bottom: 28px; }
.mobile-logo-img { width: 130px; height: auto; background: white; border-radius: 16px; padding: 12px 16px; }

/* Tabs */
.tabs {
    display: flex;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    padding: 4px;
    position: relative;
    margin-bottom: 32px;
}
.tab {
    flex: 1; border: none; background: transparent;
    padding: 10px 16px; font-size: 0.875rem; font-weight: 600;
    color: rgba(255,255,255,0.35); cursor: pointer; font-family: inherit;
    position: relative; z-index: 1; transition: color 0.2s; border-radius: 9px;
}
.tab.active { color: #1A202C; }
.tab-track {
    position: absolute; top: 4px;
    width: calc(50% - 4px); height: calc(100% - 8px);
    background: white; border-radius: 9px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.3);
    transition: left 0.25s cubic-bezier(0.4,0,0.2,1);
}
.track-left { left: 4px; }
.track-right { left: calc(50%); }

/* Formulario */
.form-head { margin-bottom: 28px; }
.form-title { font-size: 1.5rem; font-weight: 800; color: white; letter-spacing: -0.3px; margin-bottom: 6px; }
.form-sub { font-size: 0.82rem; color: rgba(255,255,255,0.4); }

.msg-success {
    background: rgba(72,187,120,0.12); border: 1px solid rgba(72,187,120,0.25);
    color: #9AE6B4; border-radius: 10px; padding: 10px 14px;
    font-size: 0.82rem; font-weight: 600; margin-bottom: 20px;
}

.field { display: flex; flex-direction: column; margin-bottom: 16px; }
.flabel {
    font-size: 0.72rem; font-weight: 700; color: rgba(255,255,255,0.45);
    margin-bottom: 7px; text-transform: uppercase; letter-spacing: 0.7px;
}
.finput {
    display: flex; align-items: center; gap: 10px;
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 12px; padding: 0 14px; height: 48px;
    transition: all 0.15s;
    color: rgba(255,255,255,0.45);
}
.finput:focus-within {
    background: rgba(255,255,255,0.1);
    border-color: #C0192A;
    box-shadow: 0 0 0 3px rgba(192,25,42,0.2);
    color: rgba(255,255,255,0.7);
}
.finput.ferror { border-color: #FC8181; background: rgba(252,129,129,0.06); }
.finput input {
    flex: 1; background: transparent; border: none; outline: none;
    font-size: 0.9rem; color: white; font-family: inherit;
}
.finput input::placeholder { color: rgba(255,255,255,0.2); }
.eye { background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; color: rgba(255,255,255,0.3); transition: color 0.15s; }
.eye:hover { color: rgba(255,255,255,0.6); }
.ferr-msg { font-size: 0.75rem; color: #FC8181; margin-top: 5px; }
.fhint { font-size: 0.72rem; color: rgba(255,255,255,0.3); margin-top: 5px; }

.remember { margin-bottom: 24px; }
.remember label { display: flex; align-items: center; gap: 8px; font-size: 0.82rem; color: rgba(255,255,255,0.45); cursor: pointer; }
.chk { width: 15px; height: 15px; accent-color: #C0192A; cursor: pointer; }

.btn-primary {
    width: 100%; height: 50px;
    background: linear-gradient(135deg, #C0192A 0%, #9B1422 100%);
    color: white; border: none; border-radius: 12px;
    font-size: 0.9rem; font-weight: 700; cursor: pointer; font-family: inherit;
    box-shadow: 0 4px 20px rgba(192,25,42,0.45);
    transition: transform 0.15s, box-shadow 0.15s;
    display: flex; align-items: center; justify-content: center;
    margin-top: 8px;
    position: relative;
    overflow: hidden;
}
.btn-primary::before {
    content: '';
    position: absolute;
    top: 0; left: -100%;
    width: 60%; height: 100%;
    background: rgba(255,255,255,0.1);
    transform: skewX(-20deg);
    transition: left 0.4s;
}
.btn-primary:hover:not(:disabled)::before { left: 150%; }
.btn-primary:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(192,25,42,0.55); }
.btn-primary:active:not(:disabled) { transform: translateY(0); }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

.spinner {
    width: 20px; height: 20px;
    border: 2.5px solid rgba(255,255,255,0.25);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

.footer { text-align: center; font-size: 0.7rem; color: rgba(255,255,255,0.18); margin-top: 32px; }

@media (max-width: 900px) {
    .left { display: none; }
    .right { width: 100%; min-width: unset; padding: 40px 28px; }
    .mobile-logo { display: flex; }
}
</style>