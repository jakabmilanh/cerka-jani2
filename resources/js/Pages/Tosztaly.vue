<template>
    <div id="hatter" style="height:100%;">
        <div class="class-groups-container">
            <h2>Osztályok</h2>

            <!-- Új osztály létrehozása -->
            <form @submit.prevent="createClass" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col">
                        <label for="class-name" class="form-label">Osztály neve</label>
                        <input id="class-name" v-model="newClass.name" type="text" class="form-control"
                            placeholder="Új osztály neve..." required />
                    </div>
                    <div class="col">
                        <label for="class-desc" class="form-label">Leírás</label>
                        <input id="class-desc" v-model="newClass.description" type="text" class="form-control"
                            placeholder="(opcionális)" />
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Létrehozás</button>
                    </div>
                </div>
            </form>

            <!-- Osztályok listája -->
            <div v-for="group in classGroups" :key="group.id" class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ group.name }}</h5>
                    <p v-if="group.description" class="card-text">{{ group.description }}</p>

                    <!-- Diákok -->
                    <ul class="list-group mb-3">
                        <li v-for="student in classStudents[group.id]" :key="student.id"
                            class="list-group-item d-flex justify-content-between align-items-center">
                            {{ student.name }} ({{ student.email }})
                            <button class="btn btn-sm btn-danger"
                                @click="removeStudent(group.id, student.id)">Eltávolítás</button>
                        </li>
                        <li v-if="!classStudents[group.id].length" class="list-group-item text-muted">Nincs diák ebben az
                            osztályban.</li>
                    </ul>

                    <!-- Diák keresése és hozzáadása -->
                    <div class="input-group">
                        <input type="text" v-model="groupSearch[group.id]" @input="searchStudents(group.id)"
                            class="form-control" placeholder="Keress diákot név vagy email alapján..." />
                        <button class="btn btn-outline-secondary" @click="addStudent(group.id, selectedForGroup[group.id])"
                            :disabled="!selectedForGroup[group.id]">
                            +
                        </button>
                    </div>
                    <ul class="list-group mt-2" v-if="groupResults[group.id].length">
                        <li v-for="student in groupResults[group.id]" :key="student.id"
                            class="list-group-item list-group-item-action" @click="selectStudent(group.id, student)">
                            {{ student.name }} ({{ student.email }})
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>
import axios from 'axios';

export default {
    name: 'ClassGroups',
    data() {
        return {
            newClass: { name: '', description: '' },
            classGroups: [],
            classStudents: {},
            groupSearch: {},
            groupResults: {},
            selectedForGroup: {}
        };
    },
    computed: {
        assignedStudentIds() {
            return new Set(Object.values(this.classStudents).flat().map(s => s.id));
        }
    },
    methods: {
        async loadClassGroups() {
            const { data } = await axios.get('/class-groups');
            this.classGroups = data;
            data.forEach(g => {
                this.classStudents[g.id] = [];
                this.groupSearch[g.id] = '';
                this.groupResults[g.id] = [];
                this.selectedForGroup[g.id] = null;
                this.fetchStudents(g.id);
            });
        },
        async createClass() {
            if (!this.newClass.name.trim()) return;
            const { data } = await axios.post('/class-groups', {
                name: this.newClass.name,
                description: this.newClass.description || null
            });
            this.classGroups.push(data);
            this.classStudents[data.id] = [];
            this.groupSearch[data.id] = '';
            this.groupResults[data.id] = [];
            this.selectedForGroup[data.id] = null;
            this.newClass.name = '';
            this.newClass.description = '';
        },
        async fetchStudents(groupId) {
            const { data } = await axios.get(`/class-groups/${groupId}/students`);
            this.classStudents[groupId] = data;
        },
        async searchStudents(groupId) {
            const q = this.groupSearch[groupId];
            if (!q || q.length < 3) {
                this.groupResults[groupId] = [];
                return;
            }
            const { data } = await axios.get('/students', { params: { query: q } });
            // kliens oldali extra szűrés
            this.groupResults[groupId] = data.filter(s => !this.assignedStudentIds.has(s.id));
        },
        selectStudent(groupId, student) {
            this.selectedForGroup[groupId] = student.id;
            this.groupSearch[groupId] = student.name;
            this.groupResults[groupId] = [];
        },
        async addStudent(groupId, studentId) {
            if (!studentId) return;
            try {
                await axios.post(`/class-groups/${groupId}/students`, { user_id: studentId });
                this.fetchStudents(groupId);
                this.groupSearch[groupId] = '';
                this.selectedForGroup[groupId] = null;
            } catch (e) {
                alert(e.response.data.message || 'Hiba történt');
            }
        },
        async removeStudent(groupId, studentId) {
            await axios.delete(`/class-groups/${groupId}/students/${studentId}`);
            this.fetchStudents(groupId);
        }
    },
    mounted() {
        this.loadClassGroups();
    }
};
</script>
  
<style scoped>
#hatter {
    background: linear-gradient(to bottom, #fff, #3d79e7);
}

.class-groups-container {
    padding: 1rem;
}

.card {
    border-radius: 0.5rem;
}

.input-group .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}</style>