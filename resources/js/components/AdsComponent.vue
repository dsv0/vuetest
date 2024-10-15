<template>
  <div>
    <h2>Ad Management</h2>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Content</th>
          <th>Status</th>
          <th>URL</th>
          <th>Campaign</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="ad in ads" :key="ad.id">
          <td>{{ ad.id }}</td>
          <td>{{ ad.title }}</td>
          <td>{{ ad.content }}</td>
          <td>{{ ad.status }}</td>
          <td>{{ ad.url }}</td>
          <td>{{ ad.campaign.title }}</td>
          <td>
            <button class="btn btn-danger" @click="deleteAd(ad.id)">Удалить</button>
            <button class="btn btn-warning" @click="editAd(ad.id)">Обновить контент</button>
            <button class="btn btn-info" @click="updateBudget(ad.id)">Обновить бюджет</button>
          </td>
        </tr>
      </tbody>
    </table>
    <button class="btn btn-primary" @click="showCreateModal = true">Создать новое объявление</button>
    <div v-if="showCreateModal" class="modal">
      <div class="modal-content">
        <span class="close" @click="showCreateModal = false">&times;</span>
        <h2>Create Ad</h2>
        <form @submit.prevent="createAd">
          <div>
            <label for="title">Title</label>
            <input type="text" v-model="newAd.title" required>
          </div>
          <div>
            <label for="content">Content</label>
            <textarea v-model="newAd.content" required></textarea>
          </div>
          <div>
            <label for="status">Status</label>
            <select v-model="newAd.status">
              <option value="active">Active</option>
              <option value="pending">Pending</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div>
            <label for="url">URL</label>
            <input type="text" v-model="newAd.url" required>
          </div>
          <div>
            <label for="campaign_id">Campaign</label>
            <select v-model="newAd.campaign_id" required>
              <option v-for="campaign in campaigns" :key="campaign.id" :value="campaign.id">
                {{ campaign.title }}
              </option>
            </select>
          </div>
          <div>
            <label for="cpm">CPM</label>
            <input type="number" v-model="newAd.cpm" step="0.01" required>
          </div>
          <div>
            <label for="budget">Budget</label>
            <input type="number" v-model="newAd.budget" step="0.01" required>
          </div>
          <div>
            <label for="button_text">Button Text</label>
            <select v-model="newAd.button_text">
              <option value="Смотреть">Смотреть</option>
              <option value="Оставить заявку">Оставить заявку</option>
              <option value="Скачать">Скачать</option>
              <option value="Подробнее">Подробнее</option>
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
      ads: [],
      campaigns: [],
      showCreateModal: false,
      newAd: {
        title: '',
        content: '',
        status: 'active',
        url: '',
        campaign_id: null,
        cpm: 0.00,
        budget: 0.00,
        button_text: 'Смотреть'
      },
    };
  },
  mounted() {
    this.getAds();
    this.getCampaigns();
  },
  methods: {
    getAds() {
      axios.get('/api/admin/ads').then(response => {
        this.ads = response.data;
      });
    },
    getCampaigns() {
      axios.get('/api/admin/campaigns').then(response => {
        this.campaigns = response.data;
      });
    },
    deleteAd(id) {
      if (confirm('Are you sure you want to delete this ad?')) {
        axios.delete(`/api/admin/ads/${id}`).then(() => {
          this.getAds();
        });
      }
    },
    editAd(id) {
      const newContent = prompt('Enter new content:');
      if (newContent) {
        axios.put(`/api/admin/ads/${id}/content`, { content: newContent }).then(() => {
          this.getAds();
        });
      }
    },
    updateBudget(id) {
      const newBudget = prompt('Enter new budget:');
      if (newBudget) {
        axios.put(`/api/admin/ads/${id}/budget`, { budget: newBudget }).then(() => {
          this.getAds();
        });
      }
    },
    createAd() {
      axios.post('/api/admin/ads', this.newAd).then(() => {
        this.getAds();
        this.showCreateModal = false;
        // Сбрасываем поля формы
        this.newAd = {
          title: '',
          content: '',
          status: 'active',
          url: '',
          campaign_id: null,
          cpm: 0.00,
          budget: 0.00,
          button_text: 'Смотреть'
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
