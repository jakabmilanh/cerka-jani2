<template>
    <div class="profile-container">
      <h2 class="profile-header">Profilom</h2>
  
      <div v-if="loading" class="text-center my-5">
        Betöltés...
      </div>
  
      <form v-else @submit.prevent="saveProfile" class="row gx-4 gy-3">
        <!-- Alapadatok: csak megjelenítés -->
        <div class="col-12 col-md-6">
          <div class="mb-3">
            <label class="form-label">Név</label>
            <input
              type="text"
              class="form-control"
              :value="profile.name"
              disabled
            />
          </div>
          <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input
              type="email"
              class="form-control"
              :value="profile.email"
              disabled
            />
          </div>
        </div>
  
        <!-- Szerkeszthető mezők: birth, contact, student_card, id_card -->
        <div class="col-12 col-md-6">
          <div class="mb-3">
            <label for="birth" class="form-label">Születési dátum</label>
            <input
              id="birth"
              v-model="profile.birth"
              type="date"
              class="form-control"
            />
          </div>
          <div class="mb-3">
            <label for="contact" class="form-label">Telefonszám</label>
            <input
              id="contact"
              v-model="profile.contact"
              type="tel"
              class="form-control"
              placeholder="Pl. +36 30 123 4567"
            />
          </div>
          <div class="mb-3">
            <label for="student_card" class="form-label">Diákigazolvány szám</label>
            <input
              id="student_card"
              v-model="profile.student_card"
              type="text"
              class="form-control"
              placeholder="Pl. 12345678"
            />
          </div>
          <div class="mb-3">
            <label for="id_card" class="form-label">Személyi igazolvány szám</label>
            <input
              id="id_card"
              v-model="profile.id_card"
              type="text"
              class="form-control"
              placeholder="Pl. AB123456"
            />
          </div>
        </div>
  
        <!-- Mentés gomb -->
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-success">
            Mentés
          </button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'UserProfile',
    data() {
      return {
        loading: true,
        profile: {
          name: '',
          email: '',
          birth: '',
          contact: '',
          student_card: '',
          id_card: ''
        }
      };
    },
    methods: {
      async fetchProfile() {
        try {
          const { data } = await axios.get('/profile');
          // A backend visszaadja a teljes user-t, de mi csak ezekkel töltjük
          this.profile.name         = data.name;
          this.profile.email        = data.email;
          this.profile.birth        = data.birth   || '';
          this.profile.contact      = data.contact || '';
          this.profile.student_card = data.student_card || '';
          this.profile.id_card      = data.id_card      || '';
        } catch (err) {
          console.error('Profil lekérése sikertelen:', err);
        } finally {
          this.loading = false;
        }
      },
      async saveProfile() {
        // Csak a módosítható mezőket küldjük
        const payload = {
          birth:        this.profile.birth,
          contact:      this.profile.contact,
          student_card: this.profile.student_card,
          id_card:      this.profile.id_card
        };
        try {
          await axios.put('/profile', payload);
          alert('Profil sikeresen frissítve!');
        } catch (err) {
          console.error('Mentés sikertelen:', err);
          alert('Hiba történt a mentés során.');
        }
      }
    },
    mounted() {
      this.fetchProfile();
    }
  };
  </script>
  
  <style scoped>
  .profile-container {
    max-width: 700px;
    margin: 30px auto;
    background: #fff;
    border-radius: 8px;
    padding: 25px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  }
  
  .profile-header {
    text-align: center;
    color: #1e3a8a;
    margin-bottom: 25px;
  }
  
  @media (max-width: 767.98px) {
    .profile-container {
      padding: 15px;
      margin: 15px;
    }
  }
  </style>
  