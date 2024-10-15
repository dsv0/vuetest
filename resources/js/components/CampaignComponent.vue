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
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCampaignModal">
      Создать новую кампанию
    </button>
    <div class="modal fade" id="createCampaignModal" tabindex="-1" aria-labelledby="createCampaignModalLabel"
         aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createCampaignModalLabel">Создать кампанию</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="createCampaign">
              <div class="mb-3">
                <label for="title" class="form-label">Название кампании</label>
                <input type="text" v-model="newCampaign.title" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Статус</label>
                <select v-model="newCampaign.status" class="form-select" required>
                  <option value="active">Активен</option>
                  <option value="pending">В ожидании</option>
                  <option value="archived">В архиве</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="user_id" class="form-label">Пользователь</label>
                <select v-model="newCampaign.user_id" class="form-select" required>
                  <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.name }}
                  </option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Создать</button>
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
