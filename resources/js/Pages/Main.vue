<template>
  <div class="main-container">
    <!-- Sidebar -->
    <nav :class="['sidebar', { open: isSidebarOpen }]">
      <div class="logo-container">
        <img src="/images/cerka.png" alt="Cerka logo">
      </div>
      <div class="text-center mb-3">
        <span class="badge bg-light text-dark p-2">{{ isTeacher ? 'Tanári Felület' : 'Diák Felület' }}</span>
      </div>
      <ul>
          <li @click="switchComponent('kezdolap')" :class="{ active: activeComponent === 'kezdolap' }">Kezdőlap</li>
          <li @click="switchComponent('naptar')" :class="{ active: activeComponent === 'naptar' }">Naptár</li>
          <li @click="switchComponent(isTeacher ? 'Tosztaly' : 'osztaly')" :class="{ active: activeComponent === (isTeacher ? 'Tosztaly' : 'osztaly') }">Osztályok</li>
          <li @click="switchComponent(isTeacher ? 'Torarend' : 'orarend')" :class="{ active: activeComponent === (isTeacher ? 'Torarend' : 'orarend') }">Órarend</li>
          <li @click="switchComponent(isTeacher ? 'Tjegyek' : 'jegyek')" :class="{ active: activeComponent === (isTeacher ? 'Tjegyek' : 'jegyek') }">Jegyek</li>
          <li @click="switchComponent('profile')" :class="{ active: activeComponent === 'edit-teacher' || activeComponent === 'edit-student' }">Profil</li>
          <li @click="Logout()">Kijelentkezés</li>
        </ul>
    </nav>

    <!-- Content -->
    <div class="content-container">
      <!-- Hamburger on mobile -->
      <button class="sidebar-toggle" @click="toggleSidebar" aria-label="Toggle menu">
        ☰
      </button>
      <component
          :is="currentComponent"
          :user="!isTeacher ? $page.props.authUser : undefined"
          :teacher="isTeacher ? $page.props.authUser : undefined"
          :status="$page.props.status"
        />
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import naptar from '/resources/js/Pages/naptar.vue';
import kezdolap from '/resources/js/Pages/kezdolap.vue';
import jegyek from '/resources/js/Pages/jegyek.vue';
import orarend from '/resources/js/Pages/orarend.vue';
import osztaly from '/resources/js/Pages/osztaly.vue';
import Torarend from '/resources/js/Pages/Torarend.vue';
import Tjegyek from '/resources/js/Pages/Tjegyek.vue';
import Tosztaly from '/resources/js/Pages/Tosztaly.vue';
import EditTeacher from "/resources/js/Pages/Profile/EditTeacher.vue";
import EditStudent from "/resources/js/Pages/Profile/EditStudent.vue";

export default {
  name: 'Main',
  components: {
    naptar,
    kezdolap,
    jegyek,
    orarend,
    osztaly,
    Torarend,
    Tjegyek,
    Tosztaly,
    'edit-teacher': EditTeacher,
    'edit-student': EditStudent,
  },
  data() {
    return {
      currentComponent: 'kezdolap',
      roleList: [],
      isSidebarOpen: false,
    };
  },
  mounted() {
    fetch("http://localhost:8000/roles")
      .then(response => response.json())
      .then(data => {
        this.role = data;
      });
  },
  computed: {
    userRole() {
      let roleData = this.$page.props.auth?.user?.role;
      if (!roleData && this.roleList.length > 0) {
        roleData = this.roleList.find(r => r.id === this.$page.props.auth.user.role_id);
      }
      return roleData ? roleData.name : "";
    },
    isTeacher() {
      return this.userRole === "Tanár";
    },
    authUser() {
      return this.$page.props.authUser;
    },
    status() {
      return this.$page.props.status;
    },
    classGroupId() {
      return this.$page.props.classGroupId;
    },
    activeComponent() {
    return this.currentComponent;
  },
  },
  methods: {
    switchComponent(componentName) {
      if (componentName === "profile") {
        this.currentComponent = this.isTeacher ? "edit-teacher" : "edit-student";
      } else {
        this.currentComponent = componentName;
      }

      if (window.innerWidth <= 768) {
        this.isSidebarOpen = false;
      }
    },
     
    toggleSidebar() {
      this.isSidebarOpen = !this.isSidebarOpen;
    },
    Logout() {
      delete axios.defaults.headers.common['Authorization'];
      this.$inertia.post('/logout', {}, { preserveState: true });
    },
    async fetchRoles() {
      try {
        const response = await fetch('http://localhost:8000/roles');
        this.roleList = await response.json();
      } catch (e) {
        console.error('Unable to fetch roles:', e);
      }
    }
  },
  async mounted() {
    await this.fetchRoles();
  }
};
</script>

<style scoped>
.sidebar li {
  transition: all 0.3s ease;
}

.sidebar li:hover {
  transform: translateX(5px);
}
.sidebar li.active {
  background: #044ff0; /* kicsit világosabb kék, hogy kiemelkedjen */
  font-weight: bold;
  color: #fff;
  box-shadow: 0 0 10px rgba(79, 70, 229, 0.6);
}
/* Base layout */
.main-container {
  display: flex;
  min-height: 100vh;
  background: #F0F4F8;
}

/* Sidebar */
.sidebar {
  width: 220px;
  background: #1E3A8A;
  color: white;
  padding: 20px;
  box-sizing: border-box;
  transition: transform 0.3s ease;
}

.sidebar .logo-container {
  text-align: center;
  margin-bottom: 30px;
}

.sidebar .logo-container img {
  max-width: 100%;
  height: auto;
}

.sidebar ul {
  list-style: none;
  padding: 0;
}

.sidebar li {
  margin-bottom: 15px;
  cursor: pointer;
  padding: 10px;
  background: #23395B;
  border-radius: 5px;
  text-align: center;
  transition: background 0.3s;
}

.sidebar li:hover {
  background: #1E3A8A;
}

/* Content */
.content-container {
  flex: 1;
  padding: 20px;
  background: #FFF;
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
  overflow-y: auto;
  position: relative;
}

/* Hamburger toggle */
.sidebar-toggle {
  display: none;
  position: absolute;
  top: 15px;
  left: 15px;
  font-size: 24px;
  background: transparent;
  border: none;
  color: #1E3A8A;
  cursor: pointer;
  z-index: 1100;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .main-container {
    flex-direction: column;
  }

  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    transform: translateX(-100%);
    z-index: 1000;
  }

  .sidebar.open {
    transform: translateX(0);
  }

  .content-container {
    margin-left: 0;
    padding-top: 60px;
  }

  .sidebar-toggle {
    display: block;
  }
}
</style>
