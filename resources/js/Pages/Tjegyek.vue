<template>
  <div id="hatter" style="height: 100%;">
  <div class="grades-page">
    <h2>Jegyek kezelése</h2>

    <!-- Osztály választó -->
    <div class="mb-4">
      <label for="class-select" class="form-label">Válassz osztályt:</label>
      <select
        id="class-select"
        v-model="selectedClassId"
        @change="loadData"
        class="form-select"
      >
        <option value="">-- Válassz osztályt --</option>
        <option
          v-for="osztaly in classes"
          :key="osztaly.id"
          :value="osztaly.id"
        >
          {{ osztaly.name }}
        </option>
      </select>
    </div>

    <div v-if="students.length">
      <div class="mb-3">
        <label for="search-input" class="form-label">Keresés diák név szerint:</label>
        <input
          id="search-input"
          type="text"
          v-model.trim="searchQuery"
          class="form-control"
          placeholder="Írj be egy nevet..."
        />
      </div>

      <!-- Diákok és jegyek -->
      <div
        v-for="student in filteredStudents"
        :key="student.id"
        class="card mb-3"
      >
        <div class="card-body">
          <h5 class="card-title">{{ student.name }}</h5>

          <!-- Tantárgyak és jegyek -->
          <div
            v-for="tantargy in subjects"
            :key="tantargy.id"
            class="mb-3"
          >
            <h6 class="card-subtitle mb-2 text-muted">{{ tantargy.name }}</h6>
            <ul class="list-group mb-2">
              <li
                v-for="jegy in (student.grades[tantargy.name] || [])"
                :key="jegy.id"
                class="list-group-item d-flex justify-content-between align-items-center"
              >
                <div>
                  <strong>{{ jegy.grade }}</strong>
                  <small class="text-muted ms-2">
                    ({{ jegy.dated }})
                  </small>
                  <p
                    v-if="jegy.remarks"
                    class="mb-0 small text-secondary"
                  >
                    "{{ jegy.remarks }}"
                  </p>
                </div>
              </li>
              <li
                v-if="!(student.grades[tantargy.name] || []).length"
                class="list-group-item text-muted"
              >
                Nincs jegy ehhez a tantárgyhoz.
              </li>
            </ul>

            <!-- Jegy hozzáadása -->
            <div class="input-group">
              <input
                type="number"
                v-model.number="newGrades[student.id][tantargy.id]"
                class="form-control"
                min="1"
                max="5"
                step="0.5"
                placeholder="Új jegy"
              />
              <input
                type="text"
                v-model.trim="newRemarks[student.id][tantargy.id]"
                class="form-control"
                placeholder="Megjegyzés"
              />
              <button
                class="btn btn-success"
                @click="addGrade(student.id, tantargy.id)"
              >
                Hozzáadás
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <p v-else class="text-muted">
      Kérlek, válassz ki egy osztályt a jegyek megtekintéséhez.
    </p>
  </div>
</div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'GradesPage',
  data() {
    return {
      classes: [],     
      subjects: [],       
      students: [],        
      selectedClassId: '',  
      searchQuery: '',       
      newGrades: {},        
      newRemarks: {},       
    };
  },
  computed: {

    filteredStudents() {
      if (!this.searchQuery) {
        return this.students;
      }
      const term = this.searchQuery.toLowerCase();
      return this.students.filter((diak) =>
        diak.name.toLowerCase().includes(term)
      );
    },
  },
  methods: {

    async loadData() {
      if (!this.selectedClassId) {
        this.students = [];
        return;
      }
      try {
        const response = await axios.get('/grades', {
          params: { class_id: this.selectedClassId }
        });
        this.classes = response.data.classes;
        this.subjects = response.data.subjects;
        this.students = response.data.students;

        const gradesObj = {};
        const remarksObj = {};
        this.students.forEach((diak) => {
          gradesObj[diak.id] = {};
          remarksObj[diak.id] = {};
          this.subjects.forEach((tantargy) => {
            gradesObj[diak.id][tantargy.id] = '';
            remarksObj[diak.id][tantargy.id] = '';
          });
        });
        this.newGrades = gradesObj;
        this.newRemarks = remarksObj;
      } catch (error) {
        console.error('Hiba az adatok betöltésekor:', error);
      }
    },
    async addGrade(studentId, subjectId) {
      const gradeValue = this.newGrades[studentId][subjectId];
      const remarkValue = this.newRemarks[studentId][subjectId] || null;
      if (!gradeValue) {
        return;
      }
      try {
        await axios.post('/grades', {
          student_id: studentId,
          subject_id: subjectId,
          grade: gradeValue,
          remarks: remarkValue
        });
        await this.loadData();
      } catch (error) {
        console.error('Hiba jegy hozzáadásakor:', error);
      }
    }
  },
  async mounted() {
    try {
      const response = await axios.get('/grades');
      this.classes = response.data.classes;
      this.subjects = response.data.subjects;
    } catch (error) {
      console.error('Hiba az osztályok és tantárgyak lekérésekor:', error);
    }
  }
};
</script>

<style scoped>
#hatter{
  background: linear-gradient(to bottom, #ffffff, #3d79e7);
}
.grades-page {
  padding: 20px;
}
</style>
