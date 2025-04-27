<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};


</script>

<template>
  <Head title="Bejelentkezés" />
  <div class="bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <h2 class="text-center mb-4">Bejelentkezés</h2>

              <div v-if="status" class="alert alert-success">
                {{ status }}
              </div>

              <form @submit.prevent="submit" v-if="!isLoggedIn">
                <div class="mb-3">
                  <label for="email" class="form-label">Email cím</label>
                  <input id="email" v-model="form.email" type="email" class="form-control" required autofocus />
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Jelszó</label>
                  <input id="password" v-model="form.password" type="password" class="form-control" required
                    autocomplete="current-password" />
                </div>

                <button type="submit" class="btn btn-primary w-100">
                  Bejelentkezés
                </button>

                <div class="text-center mt-3">
                  <span>Nincs fiókod?</span>
                  <Link :href="route('register')" class="btn btn-link">Regisztráció</Link>
                </div>

                <div v-if="form.errors.email" class="alert mt-3"
                  :class="{ 'alert-success': success, 'alert-danger': !success }">
                  Helytelen e-mail-cím vagy jelszó.
                </div>

                <div v-if="form.errors.password" class="alert mt-3"
                  :class="{ 'alert-success': success, 'alert-danger': !success }">
                  Helytelen e-mail-cím vagy jelszó.
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>

