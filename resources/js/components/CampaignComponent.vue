<template>
  <div>
    <h2>Campaign Management</h2>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Status</th>
          <th>Creator</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="campaign in campaigns" :key="campaign.id">
          <td>{{ campaign.id }}</td>
          <td>{{ campaign.title }}</td>
          <td>{{ campaign.status }}</td>
          <td>{{ campaign.user.name }}</td>
          <td>
            <button class="btn btn-danger" @click="deleteCampaign(campaign.id)">Удалить</button>
            <button class="btn btn-warning" @click="updateStatus(campaign.id)">Обновить статус</button>
          </td>
        </tr>
      </tbody>
    </table>
    <button class="btn btn-primary" @click="showCreateModal = true">Создать новую кампанию</button>

    <div v-if="showCreateModal" class="modal">
      <div class="modal-content">
        <span class="close" @click="showCreateModal = false">&times;</span>
        <h2>Create Campaign</h2>
        <form @submit.prevent="createCampaign">
          <div>
            <label for="title">Title</label>
            <input type="text" v-model="newCampaign.title" required>
          </div>
          <div>
            <label for="status">Status</label>
            <select v-model="newCampaign.status">
              <option value="active">Active</option>
              <option value="pending">Pending</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div>
            <label for="user_id">User</label>
            <select v-model="newCampaign.user_id" required>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>
          </div>
          <button type="submit">Создать</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      campaigns: [],
      users: [],
      showCreateModal: false,
      newCampaign: {
        title: '',
        status: 'active',
        user_id: null
      },
    };
  },
  mounted() {
    this.getCampaigns();
    this.getUsers();
  },
  methods: {
    getCampaigns() {
      axios.get('/api/admin/campaigns').then(response => {
        this.campaigns = response.data;
      });
    },
    getUsers() {
      axios.get('/api/admin/users').then(response => {
        this.users = response.data;
      });
    },
    deleteCampaign(id) {
      if (confirm('Are you sure you want to delete this campaign?')) {
        axios.delete(`/api/admin/campaigns/${id}`).then(() => {
          this.getCampaigns();
        });
      }
    },
    updateStatus(id) {
      const newStatus = prompt('Enter new status (active, pending, archived):');
      if (newStatus) {
        axios.put(`/api/admin/campaigns/${id}/status/${newStatus}`, {}).then(() => {
          this.getCampaigns();
        });
      }
    },
    createCampaign() {
      axios.post('/api/admin/campaigns', this.newCampaign).then(() => {
        this.getCampaigns();
        this.showCreateModal = false;
        // Сбрасываем поля формы
        this.newCampaign = {
          title: '',
          status: 'active',
          user_id: null
        };
      });
    }
  }
};
</script>

<style scoped>
.table {
  width: 100%;
  margin-top: 20px;
}
.modal {
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgb(0, 0, 0);
  background-color: rgba(0, 0, 0, 0.4);
  padding-top: 60px;
}
.modal-content {
  background-color: #fefefe;
  margin: 5% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}
</style>
