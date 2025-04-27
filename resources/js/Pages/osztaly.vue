<template>
  <div  id="hatter" style="height: 100%;">
    <div class="student-classmates container py-4">
      <h2>Osztálytársak</h2>
      <ul class="list-group">
        <li v-for="s in classmates" :key="s.id" class="list-group-item">
          {{ s.name }} <small class="text-muted">({{ s.email }})</small>
        </li>
        <li v-if="!classmates.length" class="list-group-item text-muted">
          Nincsenek megjeleníthető osztálytársak.
        </li>
      </ul>
    </div>
  </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'StudentClassmates',
    data() {
      return { classmates: [] };
    },
    async mounted() {
      try {
        const { data } = await axios.get('/self/classmates');
        this.classmates = data;
      } catch (err) {
        console.error('Hiba osztálytársak lekérésekor:', err);
      }
    }
  };
  </script>
  
  <style scoped>
  #hatter{
  background: linear-gradient(to bottom, #ffffff, #3d79e7);
}
  .student-classmates h2 {
    margin-bottom: 1rem;
  }
  </style>