<template>
  <div class="container">
    <h2>User Management</h2>
    <ul v-if="users.length" class="list-group">
      <li v-for="user in users" :key="user.id" class="list-group-item d-flex justify-content-between">
        {{ user.name }} ({{ user.email }})
        <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)">Delete</button>
      </li>
    </ul>
    <p v-else>No users found.</p>
    <div class="mt-4">
      <h4>Create New User</h4>
      <form @submit.prevent="createUser">
        <div class="form-group mb-2">
          <input v-model="name" placeholder="Name" class="form-control" required />
        </div>
        <div class="form-group mb-2">
          <input v-model="email" placeholder="Email" type="email" class="form-control" required />
        </div>
        <div class="form-group mb-2">
          <input v-model="password" placeholder="Password" type="password" class="form-control" required />
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
      </form>
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
