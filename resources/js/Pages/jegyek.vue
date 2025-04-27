<template>
  <div  id="hatter" style="height: 100%;">
  <div class="student-grades container py-4">
    <h2>Saját jegyeim</h2>

    <div v-if="Object.keys(gradesBySubject).length">
      <div
        v-for="(grades, subject) in gradesBySubject"
        :key="subject"
        class="mb-4"
      >
        <h5>{{ subject }}</h5>
        <ul class="list-group">
          <li
            v-for="g in grades"
            :key="g.id"
            class="list-group-item d-flex justify-content-between align-items-center"
          >
            <div>
              <strong>{{ g.grade }}</strong>
              <small class="text-muted ms-2">({{ g.dated }})</small>
            </div>
            <span
              v-if="g.remarks"
              class="small text-secondary"
            >"{{ g.remarks }}"</span>
          </li>
        </ul>
      </div>
    </div>

    <p v-else class="text-muted">Még nincs elmentett jegyed.</p>
  </div>
</div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'StudentGrades',
  data() {
    return {
      gradesBySubject: {}
    };
  },
  async mounted() {
    try {
      const { data } = await axios.get('/self/grades');
      this.gradesBySubject = data.gradesBySubject;
    } catch (e) {
      console.error('Hiba a saját jegyek lekérésekor:', e);
    }
  }
};
</script>

<style scoped>
#hatter{
  background: linear-gradient(to bottom, #ffffff, #3d79e7);
}
.student-grades h2 {
  margin-bottom: 1rem;
}
.student-grades .list-group-item {
  background: #f9f9f9;
}
</style>
