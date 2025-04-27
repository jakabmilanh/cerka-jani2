<template>
    <div id="hatter"style="height: 100%;">
    <div class="schedule-container">

        <!-- 0) OSZTÁLYVÁLASZTÓ legördülő -->
        <div class="mb-3">
            <label class="form-label">Válassz osztályt:</label>
            <select v-model="selectedClassGroupId" class="form-select">
                <option :value="null" disabled>-- Osztálycsoport --</option>
                <option v-for="cg in classGroups" :key="cg.id" :value="cg.id">
                    {{ cg.name }} – {{ cg.description }}
                </option>
            </select>
        </div>

        <h2 class="schedule-header">Tanári órarend</h2>

        <!-- grid csak ha van kiválasztott osztály -->
        <div v-if="selectedClassGroupId" class="schedule-grid">
            <!-- időoszlop -->
            <div class="time-column" style="margin-top: auto;">
                <div v-for="slot in timeSlots" :key="slot.time" class="time-slot">
                    {{ slot.time }}–{{ slot.endTime }}
                </div>
            </div>
            <!-- napok -->
            <div class="days-column">
                <div v-for="day in days" :key="day" class="day-column">
                    <h5>{{ day }}</h5>
                    <div v-for="slot in timeSlots" :key="slot.time" class="hour-cell" @click="select(day, slot.time)">
                        <span v-if="assigned(day, slot.time)">
                            {{ assigned(day, slot.time).subject.name }}
                        </span>
                        <span v-else class="text-muted">–</span>
                    </div>
                </div>
            </div>
        </div>
        <p v-else class="text-muted">Kérlek, válassz osztályt a fenti menüből.</p>

        <!-- tantárgyválasztó modal -->
        <div v-if="showModal" class="modal-backdrop">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Tantárgy kiválasztása</h5>
                        <button class="btn-close" @click="closeModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <select v-model="selectedSubject" class="form-select">
                            <option :value="null" disabled>Válassz...</option>
                            <option v-for="sub in subjects" :key="sub.id" :value="sub.id">
                                {{ sub.name }}
                            </option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" @click="closeModal">Mégse</button>
                        <button class="btn btn-primary" @click="save">Mentés</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
    </template>
  
<script>
import axios from 'axios';

export default {
    name: 'TeacherSchedule',
    data() {
        return {
            // 1) Osztálycsoportok
            classGroups: [],
            selectedClassGroupId: null,

            currentMonday: this.getMonday(new Date()),
            days: ["Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek"],
            timeSlots: [
                { time: "08:00", endTime: "08:40" },
                { time: "08:55", endTime: "09:35" },
                { time: "09:50", endTime: "10:30" },
                { time: "10:55", endTime: "11:35" },
                { time: "11:50", endTime: "12:30" },
                { time: "12:45", endTime: "13:25" },
                { time: "13:40", endTime: "14:20" },
                { time: "14:30", endTime: "15:10" },
                { time: "15:20", endTime: "16:00" },
                { time: "16:10", endTime: "16:50" },
                { time: "17:00", endTime: "17:40" },
                { time: "17:50", endTime: "18:30" },
                { time: "18:40", endTime: "19:20" }
            ],
            subjects: [],
            assignments: [],

            showModal: false,
            selectedDay: null,
            selectedTime: null,
            selectedSubject: null,
        };
    },
    watch: {
        // 2) Amint osztályt választ a tanár, újratöltjük a beosztást
        selectedClassGroupId(newId) {
            if (newId) {
                this.loadAssignments();
            } else {
                this.assignments = [];
            }
        },
        currentMonday() {
            if (this.selectedClassGroupId) this.loadAssignments();
        }
    },
    methods: {
        // osztálycsoportok betöltése
        async loadClassGroups() {
            try {
                const { data } = await axios.get('/class-groups');
                this.classGroups = data;
                console.log('Betöltött osztálycsoportok:', this.classGroups);
            } catch (e) {
                console.error('Hiba a class-groups lekérésekor:', e);
            }
        },

        // tantárgyak betöltése
        async loadSubjects() {
            try {
                // Ha API-d /subjects helyett /api/subjects, akkor azt írd ide
                const { data } = await axios.get('/subjects');
                this.subjects = data
                console.log('Betöltött tantárgyak:', this.subjects)
            } catch (err) {
                console.error('Hiba a tantárgyak lekérésekor:', err)
            }
        },

        // heti beosztások lekérése a kiválasztott osztályhoz
        async loadAssignments() {
            const start = this.formatDate(this.currentMonday);
            const end = this.formatDate(this.addDays(this.currentMonday, 6));
            const { data } = await axios.get(
                `/class-groups/${this.selectedClassGroupId}/schedules`,
                { params: { start, end } }
            );

            const hunDays = ["Vasárnap", "Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat"];
            this.assignments = data.map(ev => ({
                dayName: hunDays[new Date(ev.date).getDay()],
                time: ev.time.slice(0, 5),
                subject: ev.subject
            }));
        },



        select(day, time) {
            this.selectedDay = day;
            this.selectedTime = time;
            const ex = this.assigned(day, time);
            this.selectedSubject = ex ? ex.subject.id : null;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        async save() {
            const payload = {
                date: this.getDateForDay(this.selectedDay),
                time: this.selectedTime,
                end_time: this.timeSlots.find(s => s.time === this.selectedTime).endTime,
                subject_id: this.selectedSubject
            };

            await axios.post(
                `/class-groups/${this.selectedClassGroupId}/schedules`,
                payload
            );

            // Ezzel újratöltöd a teljes heti beosztást:
            await this.loadAssignments();
            this.closeModal();
        },

        assigned(day, time) {
            return this.assignments.find(a =>
                a.dayName === day &&
                a.time === time
            ) || null
        },

        // segédfüggvények
        getMonday(d) {
            const date = new Date(d);
            const day = date.getDay();
            const diff = date.getDate() - day + (day === 0 ? -6 : 1);
            return new Date(date.setDate(diff));
        },
        addDays(d, n) {
            const r = new Date(d);
            r.setDate(r.getDate() + n);
            return r;
        },
        formatDate(d) {
            const Y = d.getFullYear(), M = String(d.getMonth() + 1).padStart(2, '0'), D = String(d.getDate()).padStart(2, '0');
            return `${Y}-${M}-${D}`;
        },
        getDateForDay(dayName) {
            const map = { "Hétfő": 1, "Kedd": 2, "Szerda": 3, "Csütörtök": 4, "Péntek": 5, "Szombat": 6, "Vasárnap": 0 };
            const monday = this.getMonday(this.currentMonday);
            const diff = (map[dayName] === 0 ? 6 : map[dayName] - 1);
            const target = new Date(monday);
            target.setDate(monday.getDate() + diff);
            return this.formatDate(target);
        }
    },
    watch: {
        selectedClassGroupId(newId) {
            if (newId) {
                this.loadAssignments();
            } else {
                this.assignments = [];
            }
        }
    },
    mounted() {
        this.loadClassGroups();
        this.loadSubjects();
    }
};
</script>

<style scoped>
#hatter{
  background: linear-gradient(to bottom, #ffffff, #3d79e7);
}
.schedule-container {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
}

.schedule-grid {
    display: flex;
}

.time-column {
    width: 120px;
    display: flex;
    flex-direction: column;
}

.time-slot {
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid #23395B;
    background: #E3E8F0;
    font-weight: bold;
    color: #1E3A8A;
}

.days-column {
    flex: 1;
    display: flex;
}

.day-column {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.hour-cell {
    height: 50px;
    width: 100%;
    border: 1px solid #23395B;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F0F4F8;
    cursor: pointer;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
}

.modal-dialog {
    max-width: 400px;
    width: 100%;
}

.modal-content {
    background: #fff;
    border-radius: .3rem;
    overflow: hidden;
}

.modal-header,
.modal-footer {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-body {
    padding: 1rem;
}

.btn-close {
    background: transparent;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
}
</style>
  