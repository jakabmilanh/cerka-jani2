<template>
    <div class="schedule-container">
        <div class="row">
            <!-- Naptár bal oldali rész -->
            <div class="col-md-9 col-12">
                <h2 class="schedule-header">Heti időbeosztás</h2>

                <!-- Heti navigáció -->
                <div class="container" style="margin: 0;">
                    <div class="row d-flex justify-content-between flex-wrap align-items-center mb-3">
                        <div class="col-auto p-1">
                            <button class="btn btn-primary w-100" @click="prevWeek">Előző hét</button>
                        </div>
                        <div class="col-auto p-1 text-center">
                            <span>{{ weekDisplay }}</span>
                        </div>
                        <div class="col-auto p-1">
                            <button class="btn btn-primary w-100" @click="nextWeek">Következő hét</button>
                        </div>
                    </div>
                </div>

                <!-- Heti naptár grid -->
                <div class="schedule-grid overflow-auto">
                    <div class="time-column" style="margin-top: auto;">
                        <div v-for="slot in timeSlots" :key="slot.time" class="time-slot">
                            {{ slot.time }}-{{ slot.endTime }}
                        </div>
                    </div>
                    <div class="days-column d-flex">
                        <div v-for="day in days" :key="day" class="day-column">
                            <h3 class="day-header">{{ day }}</h3>
                            <div v-for="slot in timeSlots" :key="slot.time" class="hour-cell"
                                @click="openModal(day, slot.time, slot.endTime)">
                                <div v-for="event in getEvents(day, slot.time)" :key="event.id" class="event-text">
                                    {{ truncateText(event.title, 10) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Heti programok -->
            <div class="col-md-3 col-12 mt-4 mt-md-0">
                <h2 class="schedule-header">Heti programok</h2>
                <div class="events-list" v-if="events.length">
                    <ul class="list-unstyled">
                        <li v-for="e in events" :key="e.id"
                            class="event-item mb-2 d-flex align-items-center justify-content-between">
                            <div class="event-card p-2 flex-grow-1 me-2">
                                <div class="event-time">{{ e.day }} – {{ e.time }} – {{ e.endTime }}</div>
                                <div class="event-title">{{ e.title }}</div>
                            </div>
                            <button class="btn btn-sm btn-danger" @click="deleteEvent(e.id)">
                                Törlés
                            </button>
                        </li>
                    </ul>
                </div>
                <p v-else>Nincsenek események.</p>
            </div>
        </div>

        <div v-if="showModal" class="modal-overlay">
            <div class="modal-box">
                <div class="modal-header">
                    <h5 class="modal-title">Új esemény hozzáadása</h5>
                    <button class="btn-close" @click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <input v-model="newEventTitle" type="text" class="form-control" placeholder="Esemény címe" />
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" @click="closeModal">Mégse</button>
                    <button class="btn btn-primary" @click="saveEvent">Mentés</button>
                </div>
            </div>
        </div>
    </div>
</template>



  
<script>
import axios from 'axios';

export default {
    name: "Torarend",
    data() {
        return {
            days: ["Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat", "Vasárnap"],
            // currentMonday a heti időintervallum kezdő dátumát tartalmazza – a hétfőt
            currentMonday: getMonday(new Date()),
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
            events: [],
            showModal: false,
            newEventTitle: "",
            selectedDay: "",
            selectedTime: "",
            selectedEndTime: ""
        };
    },
    computed: {
        weekDisplay() {
            console.log("asd");
            const start = this.currentMonday
            const end = new Date(start)
            end.setDate(start.getDate() + 6)
            return `${formatDate(start)} - ${formatDate(end)}`
        },
    },
    methods: {
        async deleteEvent(id) {
            if (!confirm('Biztosan törlöd ezt az eseményt?')) {
                return;
            }
            try {
                await axios.delete(`http://localhost:8000/events/${id}`);
                this.fetchEvents();
            } catch (err) {
                console.error('Törlés sikertelen:', err);
                alert('Hiba történt a törlés során.');
            }
        },
        prevWeek() {
            const newMon = new Date(this.currentMonday)
            newMon.setDate(newMon.getDate() - 7)
            this.currentMonday = newMon
            this.fetchEvents()
        },
        nextWeek() {
            const newMon = new Date(this.currentMonday)
            newMon.setDate(newMon.getDate() + 7)
            this.currentMonday = newMon
            this.fetchEvents()
        },
        fetchEvents() {
            const monday = this.currentMonday;
            const startOfWeek = formatDate(monday);

            const sunday = new Date(monday);
            sunday.setDate(monday.getDate() + 6);
            const endOfWeek = formatDate(sunday);

            axios.get(`http://localhost:8000/api/events?start=${startOfWeek}&end=${endOfWeek}`)
                .then(response => {
                    const hunDays = ["Vasárnap", "Hétfő", "Kedd", "Szerda", "Csütörtök", "Péntek", "Szombat"];
                    this.events = response.data.map(event => {
                        const dateObj = new Date(event.day);
                        const dayName = hunDays[dateObj.getDay()];
                        return {
                            ...event,
                            date: event.day,
                            dayName,
                            time: event.time?.slice(0, 5) ?? "",
                            endTime: event.end_time?.slice(0, 5) ?? ""
                        };
                    });
                });
        },
        openModal(day, time, endTime) {
            this.selectedDay = day;
            this.selectedTime = time;
            this.selectedEndTime = endTime;
            this.newEventTitle = "";
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },
        saveEvent() {
            if (!this.newEventTitle.trim()) return;
            const date = this.getDateForDay(this.selectedDay);
            const newEvent = {
                id: Date.now(),
                date: date,
                day: this.selectedDay,
                time: this.selectedTime,
                endTime: this.selectedEndTime,
                title: this.newEventTitle
            };
            this.events.push(newEvent);
            axios.post("http://localhost:8000/api/events", newEvent, {
                headers: { "Content-Type": "application/json" }
            })
                .then(response => console.log("Event saved:", response.data))
                .then(this.fetchEvents())
                .catch(error => console.error("Error saving event:", error));
            this.closeModal();
        },
        getEvents(day, time) {
            // hétfő éjfélre állítjuk
            const monday = new Date(this.currentMonday);
            monday.setHours(0, 0, 0, 0);
            const startTs = monday.getTime();

            // vasárnap 23:59:59.999-re állítjuk
            const sunday = new Date(this.currentMonday);
            sunday.setDate(sunday.getDate() + 6);
            sunday.setHours(23, 59, 59, 999);
            const endTs = sunday.getTime();

            return this.events.filter(evt => {
                const evtTs = new Date(evt.date).getTime();
                return (
                    evtTs >= startTs &&
                    evtTs <= endTs &&
                    evt.dayName === day &&
                    evt.time === time
                );
            });
        },
        truncateText(text, length) {
            return text.length > length ? text.substring(0, length) + "..." : text;
        },
        // Példa metódus arra, hogy kiszámolja a megadott nap dátumát a jelenlegi hét alapján
        getDateForDay(dayName) {
            const mapping = {
                "Hétfő": 1,
                "Kedd": 2,
                "Szerda": 3,
                "Csütörtök": 4,
                "Péntek": 5,
                "Szombat": 6,
                "Vasárnap": 7

            };
            let currentMonday = new Date(this.currentMonday);
            let targetDate = new Date(currentMonday);
            const currentDayIndex = currentMonday.getDay();
            const targetDayIndex = mapping[dayName];
            // A currentMonday a hétfő. Ha a hétfőnek getDay() értéke nem 1, akkor a currentMonday már a helyes hétfő (lásd getMonday() segédfüggvény)
            let diff = targetDayIndex - 1; // mert a hétfő indexe 1
            targetDate.setDate(currentMonday.getDate() + diff);
            return formatDate(targetDate);
        },
    },
    mounted() {
        this.fetchEvents();
    },
};

// Segédfüggvények a komponens végén, de a <script> block-on belül
function getMonday(d) {
    d = new Date(d);
    const day = d.getDay();
    const diff = d.getDate() - day + (day === 0 ? -6 : 1);
    return new Date(d.setDate(diff));
}

function formatDate(date) {
    const year = date.getFullYear();
    let month = (date.getMonth() + 1).toString();
    let day = date.getDate().toString();
    if (month.length < 2) month = "0" + month;
    if (day.length < 2) day = "0" + day;
    return `${year}-${month}-${day}`;
}
</script>

<style scoped>
/* Konténer a táblázatnak és a modalnak */
.schedule-container {
    margin: 0;
    width: auto;
    background: #fff;
    border-radius: 10px;
}

.schedule-table th,
.schedule-table td {
    vertical-align: middle;
    text-align: center;
    cursor: pointer;
}

/* Az esemény rövid szövege a cellában */
.event-text {
    display: inline-block;
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Eseménylista stílusok */
.events-list {
    margin-top: 20px;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
}

.event-item {
    margin-bottom: 10px;
}

.event-card {
    background: #fff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.event-time {
    font-weight: bold;
    color: #1e3a8a;
}

/* Modal (felugró ablak) */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    width: 300px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-body {
    margin: 10px 0;
}

.btn-close {
    background: transparent;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
}




.main-container {
    display: flex;
    background: #F0F4F8;
    height: 100vh;
}

.sidebar {
    width: 200px;
    background: #1E3A8A;
    color: white;
    padding: 20px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar li {
    margin-bottom: 15px;
}

.sidebar a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 10px;
    background: #23395B;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
}

.sidebar a:hover {
    background: #1E3A8A;
}

.content-container {
    flex: 1;
    margin-left: 220px;
    padding: 20px;
    background: #FFFFFF;
    border-radius: 10px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    overflow-y: auto;
}

.schedule-container {
    background: #FFFFFF;
    border-radius: 10px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    padding: 20px;
    width: 100%;
    overflow-y: auto;
}

.schedule-header {
    text-align: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin-bottom: 10px;
    color: #1E3A8A;
}

.schedule-grid {
    display: flex;
    align-items: stretch;
    width: 100%;
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
    color: #1E3A8A;
    font-weight: bold;
    background: #E3E8F0;

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
    border: 1px solid #23395B;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    cursor: pointer;
    background: #F0F4F8;
    width: 100%;
}
</style>