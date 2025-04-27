<script setup>
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
  roles: {
    type: Array,
    default: () => [],
  },
});

// Űrlap adatainak kezelése
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role_id: '',
});

// Opcionális visszajelzés (üzenet)
const message = '';
const success = true;

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>

  <Head title="Register" />

  <!-- A container a bootstrap grid segítségével középre igazítja az űrlapot -->
  <div class="container d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <form @submit.prevent="submit" class="col-12 col-md-6 col-lg-4 bg-white p-4 rounded shadow">
      <h2 class="text-center mb-4 text-primary">Regisztráció</h2>

      <div class="mb-3">
        <label for="name" class="form-label">Név</label>
        <input v-model="form.name" type="text" class="form-control" id="name" required />
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email cím</label>
        <input v-model="form.email" type="email" class="form-control" id="email" required />
      </div>

      <div class="mb-3">
        <label for="role" class="form-label">Szerepkör</label>
        <select v-model="form.role_id" class="form-select" id="role" required>
          <option disabled value="">Válassz szerepkört</option>
          <option v-for="r in roles" :key="r.id" :value="r.id">
            {{ r.name }}
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Jelszó</label>
        <input v-model="form.password" type="password" class="form-control" id="password" required />
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Jelszó megerősítése</label>
        <input v-model="form.password_confirmation" type="password" class="form-control" id="password_confirmation"
          required />
      </div>
      <button type="submit" class="btn btn-primary w-100">Regisztráció</button>
      <div class="mt-2 text-center">
        <a href="login">Már van fiókom.</a>
      </div>
      <div v-if="form.errors.password" class="alert mt-3" :class="{ 'alert-success': success, 'alert-danger': !success }">
        {{ form.errors.password }}
      </div>
    </form>
  </div>
</template>



<style scoped></style>
