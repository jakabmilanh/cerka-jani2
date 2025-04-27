<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

const props = defineProps({
  user: Object,
  status: String
})

const form = useForm({
  address: props.user?.address || '',
  birth: props.user?.birth || '',
  contact: props.user?.contact || '',
  id_card: props.user?.id_card || '',
  student_card: props.user?.student_card || '',
})

const showStatus = ref(false)

watch(() => props.status, (val) => {
  if (val) {
    showStatus.value = true
    setTimeout(() => (showStatus.value = false), 4000)
  }
})

const submit = () => {
  form.put('/student/profile', {
    preserveScroll: true,
    onSuccess: () => {
      console.log("Mentés sikeres")
    }
  })
}

</script>

<template>
  <div  id="hatter" class="col-12" style="height: 100%;">
  <div class="px-3 py-4">
    <div class="card shadow border-0 mx-auto" style="max-width: 720px;">
      <div class="card-header bg-primary text-white text-center">
        <i class="bi bi-person-circle fs-3"></i>
        <h2 class="mb-0" style="font-family: Georgia, 'Times New Roman', Times, serif;">Diák profil szerkesztése</h2>
      </div>

      <div class="card-body">
        <div v-if="showStatus" class="alert alert-success text-center mb-4 fade-in">
          {{ status }}
        </div>

        <form @submit.prevent="submit">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Cím</label>
              <input v-model="form.address" class="form-control" placeholder="Pl. Eger, Rákóczi u. 12." />
            </div>
            <div class="col-md-6">
              <label class="form-label">Születési dátum</label>
              <input v-model="form.birth" type="date" class="form-control" />
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Elérhetőség</label>
              <input v-model="form.contact" class="form-control" placeholder="+36 30 123 4567" />
            </div>
            <div class="col-md-6">
              <label class="form-label">Személyi igazolvány szám</label>
              <input v-model="form.id_card" class="form-control" placeholder="ABC123456" />
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Diákigazolvány szám</label>
            <input v-model="form.student_card" class="form-control" placeholder="123456AB" />
          </div>

          <div class="text-center">
            <button :disabled="form.processing" type="submit" class="btn btn-primary px-5">
              {{ form.processing ? 'Mentés...' : 'Mentés' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
</template>

<style scoped>
.card-header {
  padding: 1.5rem;
  font-size: 1.5rem;
  font-weight: bold;
  box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.1);
}
#hatter{
  background: linear-gradient(to bottom, #ffffff, #3d79e7);
}
.card {
  border-radius: 1rem;
  animation: popIn 0.4s ease-out;
}
.btn-primary {
  background-color: #1E3A8A;
  border-color: #1E3A8A;
  padding: 0.75rem 2rem;
  font-size: 1.125rem; /* nagyobb betű */
  border-radius: 0.75rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background-color: #163372;
  border-color: #163372;
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.fade-in {
  animation: fadeInSlide 0.6s ease-out;
}
@keyframes popIn {
  0% { opacity: 0; transform: scale(0.95); }
  100% { opacity: 1; transform: scale(1); }
}
@keyframes fadeInSlide {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.form-control {
  border-radius: 0.5rem;
  padding: 0.75rem 1rem;
  box-shadow: none;
  transition: box-shadow 0.5s ease;
}

.form-control:focus {
  border-color: #3666ec;
  box-shadow: 0 0 0 0.2rem rgba(30, 58, 138, 0.25);
}
</style>
