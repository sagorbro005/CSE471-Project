<template>
  <div class="admin-blogs">
    <AdminSidebar />
    <div class="blogs-content">
      <div class="blogs-header">
        <h2>Manage Blogs</h2>
        <button class="add-blog-btn" @click="openAddModal">+ Add New Blog</button>
      </div>
      <table class="blogs-table">
        <thead>
          <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="blog in blogs" :key="blog.id">
            <td>
              <img v-if="blog.image" :src="getImageUrl(blog.image)" alt="Blog Image" class="blog-thumb" />
              <span v-else class="no-img">No Image</span>
            </td>
            <td>{{ blog.title }}</td>
            <td>{{ blog.category }}</td>
            <td><span :class="{'active': blog.status}">{{ blog.status ? 'Active' : 'Inactive' }}</span></td>
            <td>{{ new Date(blog.created_at).toLocaleString() }}</td>
            <td>
              <a href="#" class="edit-link" @click.prevent="openEditModal(blog)">Edit</a> |
              <a href="#" class="delete-link" @click.prevent="deleteBlog(blog)">Delete</a>
            </td>
          </tr>
        </tbody>
      </table>
      <!-- Add/Edit Blog Modal -->
      <div v-if="showModal" class="modal-bg">
        <div class="modal-content">
          <h3>{{ editMode ? 'Edit Blog' : 'Add New Blog' }}</h3>
          <form @submit.prevent="editMode ? submitEditBlog() : submitBlog()" enctype="multipart/form-data">
            <div class="form-group">
              <label>Title</label>
              <input v-model="form.title" required />
            </div>
            <div class="form-group">
              <label>Category</label>
              <select v-model="form.category" required>
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
              </select>
            </div>
            <div class="form-group">
              <label>Content</label>
              <textarea v-model="form.content" required></textarea>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select v-model="form.status" required>
                <option :value="true">Active</option>
                <option :value="false">Inactive</option>
              </select>
            </div>
            <div class="form-group">
              <label>Image</label>
              <input type="file" @change="handleImage" accept="image/*" />
              <div v-if="form.imagePreview">
                <img :src="form.imagePreview" class="preview-thumb" />
              </div>
              <div v-else-if="editMode && form.image">
                <img :src="`/storage/${form.image}`" class="preview-thumb" />
              </div>
            </div>
            <div class="modal-actions">
              <button type="submit" class="add-blog-btn">{{ editMode ? 'Update Blog' : 'Post Blog' }}</button>
              <button type="button" class="cancel-btn" @click="closeModal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminSidebar from './AdminSidebar.vue';
import { imageHelper } from '@/mixins/ImageHelper.js';

const page = usePage();
const blogs = ref(page.props.blogs ?? []);
const categories = page.props.categories ?? [];

// Import ImageHelper method for displaying Cloudinary images
const { getImageUrl } = imageHelper.methods;

const showModal = ref(false);
const editMode = ref(false);
const editingBlog = ref(null);
const form = ref({ title: '', category: '', content: '', status: true, image: null, imagePreview: null });

function openAddModal() {
  showModal.value = true;
  editMode.value = false;
  editingBlog.value = null;
  form.value = { title: '', category: '', content: '', status: true, image: null, imagePreview: null };
}

function openEditModal(blog) {
  showModal.value = true;
  editMode.value = true;
  editingBlog.value = blog;
  form.value = {
    title: blog.title,
    category: blog.category,
    content: blog.content,
    status: blog.status,
    image: blog.image,
    imagePreview: null
  };
}

function closeModal() {
  showModal.value = false;
  editMode.value = false;
  editingBlog.value = null;
  form.value = { title: '', category: '', content: '', status: true, image: null, imagePreview: null };
}

function handleImage(e) {
  const file = e.target.files[0];
  if (file) {
    form.value.image = file;
    form.value.imagePreview = URL.createObjectURL(file);
  }
}

function submitBlog() {
  const data = new FormData();
  data.append('title', form.value.title);
  data.append('category', form.value.category);
  data.append('content', form.value.content);
  data.append('status', form.value.status ? 1 : 0);
  if (form.value.image instanceof File) {
    data.append('image', form.value.image);
  }
  router.post('/admin/blogs', data, {
    forceFormData: true,
    preserveState: false, // Don't preserve state, reload fresh data
    onSuccess: () => {
      closeModal();
    }
  });
}

function submitEditBlog() {
  const data = new FormData();
  data.append('title', form.value.title);
  data.append('category', form.value.category);
  data.append('content', form.value.content);
  data.append('status', form.value.status ? 1 : 0);
  if (form.value.image instanceof File) {
    data.append('image', form.value.image);
  }
  router.post(`/admin/blogs/${editingBlog.value.id}`, data, {
    forceFormData: true,
    preserveState: false,
    onSuccess: () => {
      closeModal();
    }
  });
}

function deleteBlog(blog) {
  if (confirm('Are you sure you want to delete this blog?')) {
    router.delete(`/admin/blogs/${blog.id}`, {
      preserveState: false, // Don't preserve state, reload fresh data
      onSuccess: () => {
        // No-op, will refresh
      }
    });
  }
}
</script>

<style scoped>
.admin-blogs {
  display: flex;
  min-height: 100vh;
}
.blogs-content {
  flex: 1;
  padding: 48px 40px 40px 40px;
  background: #f4f7fb;
}
.blogs-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}
.add-blog-btn {
  background: #2563eb;
  color: #fff;
  border: none;
  padding: 10px 22px;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
.add-blog-btn:hover {
  background: #1d4ed8;
}
.blogs-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(30,41,59,0.08);
}
th, td {
  border: 1px solid #e5e7eb;
  padding: 12px 14px;
  text-align: left;
}
th {
  background: #f1f5f9;
  color: #222e3c;
  font-weight: 700;
}
.active {
  background: #e6ffe6;
  color: #22bb33;
  padding: 3px 8px;
  border-radius: 5px;
}
.blog-thumb {
  width: 60px;
  height: 44px;
  object-fit: cover;
  border-radius: 5px;
  border: 1px solid #dbeafe;
  background: #f4f7fb;
}
.no-img {
  color: #888;
  font-size: 0.95rem;
}
/* Modal Styles */
.modal-bg {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.18);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-content {
  background: #fff;
  padding: 32px 30px 24px 30px;
  border-radius: 10px;
  min-width: 350px;
  box-shadow: 0 6px 24px rgba(0,0,0,0.11);
}
.form-group {
  margin-bottom: 1.1rem;
}
.form-group label {
  font-weight: 600;
  margin-bottom: 0.3rem;
  display: block;
}
.form-group input, .form-group select, .form-group textarea {
  width: 100%;
  padding: 0.6rem;
  border: 1px solid #dbeafe;
  border-radius: 6px;
  font-size: 1rem;
  outline: none;
  margin-top: 0.2rem;
}
.form-group textarea {
  min-height: 80px;
}
.preview-thumb {
  width: 100px;
  height: 70px;
  object-fit: cover;
  margin-top: 10px;
  border-radius: 6px;
  border: 1px solid #dbeafe;
}
.modal-actions {
  display: flex;
  gap: 14px;
  justify-content: flex-end;
}
.cancel-btn {
  background: #e5e7eb;
  color: #222e3c;
  border: none;
  padding: 10px 22px;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
}
.cancel-btn:hover {
  background: #cbd5e1;
}
.edit-link {
  color: #2563eb;
  font-weight: 600;
  text-decoration: none;
}
.delete-link {
  color: #e53e3e;
  font-weight: 600;
  text-decoration: none;
}
.edit-link:hover, .delete-link:hover {
  text-decoration: underline;
}
</style>
