<template>
  <div class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg relative">
      <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-600" @click="$emit('close')">&times;</button>
      <form @submit.prevent="updateUser" class="space-y-6">
        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input v-model="form.name" type="text" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
        </div>
        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input v-model="form.email" type="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
        </div>
        <!-- Phone -->
        <div>
          <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
          <input v-model="form.phone" type="text" id="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
          <p v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</p>
        </div>
        <!-- Gender -->
        <div>
          <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
          <select v-model="form.gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
          <p v-if="errors.gender" class="mt-1 text-sm text-red-600">{{ errors.gender }}</p>
        </div>
        <!-- Date of Birth -->
        <div>
          <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
          <input v-model="form.date_of_birth" type="date" id="date_of_birth" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
          <p v-if="errors.date_of_birth" class="mt-1 text-sm text-red-600">{{ errors.date_of_birth }}</p>
        </div>
        <!-- Address -->
        <div>
          <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
          <textarea v-model="form.address" id="address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
          <p v-if="errors.address" class="mt-1 text-sm text-red-600">{{ errors.address }}</p>
        </div>
        <div class="flex justify-end space-x-4">
          <button type="button" @click="$emit('close')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
          <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">Update User</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'AdminUsersEdit',
  props: {
    user: { type: Object, required: true },
  },
  data() {
    return {
      form: { ...this.user },
      errors: {},
    };
  },
  methods: {
    updateUser() {
      axios
        .put(`/admin/users/${this.form.id}`, this.form)
        .then(() => {
          this.$emit('updated', 'User updated successfully!');
        })
        .catch((err) => {
          if (err.response && err.response.data && err.response.data.errors) {
            this.errors = err.response.data.errors;
          } else {
            alert('An error occurred.');
          }
        });
    },
  },
  watch: {
    user(newUser) {
      this.form = { ...newUser };
      // Ensure date_of_birth is in YYYY-MM-DD format for input type="date"
      if (this.form.date_of_birth) {
        this.form.date_of_birth = this.form.date_of_birth.slice(0, 10);
      }
      this.errors = {};
    },
  },
};
</script>

<style scoped>
.fixed { z-index: 9999; }
</style>
