<template>
    <div id="hatter" style="height: 100%;">
    <div class="schedule-container">
        <h2 class="schedule-header">Saját tanórarended</h2>

        <div v-if="assignments.length" class="schedule-grid">
            <!-- időoszlop -->
            <div class="time-column">
                <div v-for="slot in timeSlots" :key="slot.time" class="time-slot">
                    {{ slot.time }}–{{ slot.endTime }}
                </div>
            </div>

            <!-- napok -->
            <div class="days-column">
                <div v-for="day in days" :key="day" class="day-column">
                    <h5 class="day-header">{{ day }}</h5>
                    <div v-for="slot in timeSlots" :key="slot.time" class="hour-cell">
                        <span v-if="assigned(day, slot.time)">
                            {{ assigned(day, slot.time).subject.name }}
                        </span>
                        <span v-else class="text-muted">–</span>
                    </div>
                </div>
            </div>
        </div>

        <p v-else class="text-muted">Nincs órarend ehhez a héthez.</p>
    </div>
</div>
</template>
  
<script>
import axios from 'axios';

export default {
    name: 'StudentSchedule',
    data() {
        return {
            currentMonday: this.getMonday(new Date()),
            days: ['Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek'],
            timeSlots: [
                { time: '08:00', endTime: '08:40' },
                { time: '08:55', endTime: '09:35' },
                { time: '09:50', endTime: '10:30' },
                { time: '10:55', endTime: '11:35' },
                { time: '11:50', endTime: '12:30' },
                { time: '12:45', endTime: '13:25' },
                { time: '13:40', endTime: '14:20' },
                { time: '14:30', endTime: '15:10' },
                { time: '15:20', endTime: '16:00' },
                { time: '16:10', endTime: '16:50' },
                { time: '17:00', endTime: '17:40' },
                { time: '17:50', endTime: '18:30' },
                { time: '18:40', endTime: '19:20' }
            ],
            assignments: []
        };
    },
    methods: {
        async loadAssignments() {
            const start = this.formatDate(this.currentMonday);
            const end = this.formatDate(this.addDays(this.currentMonday, 6));
            try {
                const { data } = await axios.get('/self/schedule', { params: { start, end } });
                const hunDays = ['Vasárnap', 'Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat'];
                this.assignments = data.map(ev => ({
                    dayName: hunDays[new Date(ev.date).getDay()],
                    time: ev.time.slice(0, 5),
                    subject: ev.subject
                }));
            } catch {
                this.assignments = [];
            }
        },
        assigned(day, time) {
            return this.assignments.find(a => a.dayName === day && a.time === time) || null;
        },
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
            const Y = d.getFullYear();
            const M = String(d.getMonth() + 1).padStart(2, '0');
            const D = String(d.getDate()).padStart(2, '0');
            return `${Y}-${M}-${D}`;
        }
    },
    mounted() {
        this.loadAssignments();
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
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    padding: 20px;
    width: 100%;
}

/* Címsor */
.schedule-header {
    text-align: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 15px;
    color: #1e3a8a;
}

/* Alap flex layout és scroll engedélyezése */
.schedule-grid {
    display: flex;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    align-items: stretch;
    width: 100%;
}

/* Ne zsugorodjanak össze */
.time-column,
.day-column {
    flex-shrink: 0;
}

/* Időoszlop */
.time-column {
    width: 150px;
    display: flex;
    flex-direction: column;
    margin-top: auto;
}

.time-slot {
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-bottom: 1px solid #23395b;
    background: #e3e8f0;
    font-weight: bold;
    color: #1e3a8a;
}

/* Napok oszlopai */
.days-column {
    display: flex;
}

.day-column {
    min-width: 120px;
    width: 280px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.day-header {
    margin-bottom: 5px;
}

/* Cellák */
.hour-cell {
    height: 50px;
    width: 100%;
    border: 1px solid #23395b;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f4f8;
}

/* Mobilon kisebb cellák, betűméret */
@media (max-width: 767.98px) {

    .time-slot,
    .hour-cell {
        height: 40px;
        font-size: 0.85rem;
    }
    .day-column
    {
        width: auto;
    }

    .time-column
    {
        width: auto;
    }

    .schedule-header {
        font-size: 1.25rem;
    }
}
</style>
  