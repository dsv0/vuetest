<template>
  <div>
    <h2>Ads Management</h2>
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
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAdModal">Создать новое объявление</button>

    <div class="modal fade" id="createAdModal" tabindex="-1" aria-labelledby="createAdModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createAdModalLabel">Создать объявление</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="createAd">
              <div class="mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" v-model="newAd.title" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="content" class="form-label">Содержимое</label>
                <textarea v-model="newAd.content" class="form-control" required></textarea>
              </div>
              <div class="mb-3">
                <label for="status" class="form-label">Статус</label>
                <select v-model="newAd.status" class="form-select" required>
                  <option value="active">Активен</option>
                  <option value="pending">В ожидании</option>
                  <option value="archived">В архиве</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="url" class="form-label">URL</label>
                <input type="text" v-model="newAd.url" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="campaign_id" class="form-label">Кампания</label>
                <select v-model="newAd.campaign_id" class="form-select" required>
                  <option v-for="campaign in campaigns" :key="campaign.id" :value="campaign.id">
                    {{ campaign.title }}
                  </option>
                </select>
              </div>
              <div class="mb-3">
                <label for="cpm" class="form-label">CPM</label>
                <input type="number" v-model="newAd.cpm" step="0.01" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="budget" class="form-label">Бюджет</label>
                <input type="number" v-model="newAd.budget" step="0.01" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="button_text" class="form-label">Текст кнопки</label>
                <select v-model="newAd.button_text" class="form-select">
                  <option value="Смотреть">Смотреть</option>
                  <option value="Оставить заявку">Оставить заявку</option>
                  <option value="Скачать">Скачать</option>
                  <option value="Подробнее">Подробнее</option>
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
