<template>
  <div class="container mt-4">
    <h2>User Management</h2>

    <ul v-if="users.length" class="list-group mb-4">
      <li v-for="user in users" :key="user.id" class="list-group-item d-flex justify-content-between align-items-center">
        {{ user.name }} ({{ user.email }})
        <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)">Delete</button>
      </li>
    </ul>
    <p v-else>No users found.</p>

    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">Создать нового пользователя</button>

    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createUserModalLabel">Создать пользователя</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="createUser">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input id="name" v-model="name" placeholder="Name" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" v-model="email" placeholder="Email" type="email" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" v-model="password" placeholder="Password" type="password" class="form-control" required />
              </div>
              <button type="submit" class="btn btn-primary">Создать пользователя</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      users: [],
      name: '',
      email: '',
      password: ''
    };
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    getUsers() {
      axios.get('/api/admin/users').then((response) => {
        this.users = response.data;
      });
    },
    createUser() {
      axios.post('/api/admin/users', {
        name: this.name,
        email: this.email,
        password: this.password
      }).then(() => {
        this.getUsers();
        this.name = '';
        this.email = '';
        this.password = '';
      });
    },
    deleteUser(id) {
      axios.delete(`/api/admin/users/${id}`).then(() => {
        this.getUsers();
      });
    }
  }
};
</script>
