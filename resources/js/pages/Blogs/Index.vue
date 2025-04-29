<template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Search Box -->
      <div class="max-w-2xl mx-auto">
        <div class="relative">
          <input type="text"
                 v-model="search"
                 placeholder="Search for blogs..."
                 class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                 @input="handleSearch">
          <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
            <i class="fas fa-search"></i>
          </div>
          <a v-if="search"
             @click="clearSearch"
             class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 cursor-pointer">
            <i class="fas fa-times"></i>
          </a>
        </div>
      </div>

      <!-- Category Filter -->
      <div class="mb-6">
        <h3 class="text-lg font-semibold mb-4">Categories</h3>
        <div class="flex flex-wrap gap-2">
          <button v-for="category in categories"
                  :key="category"
                  @click="selectCategory(category)"
                  :class="[
                    'px-4 py-2 rounded-full transition',
                    selectedCategory === category 
                      ? 'bg-blue-500 text-white' 
                      : 'bg-gray-200 text-gray-700 hover:bg-blue-600 hover:text-white'
                  ]">
            {{ category }}
          </button>
        </div>
      </div>

      <!-- Blogs Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6">
        <div v-for="blog in filteredBlogs" 
             :key="blog.id" 
             class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
          <img :src="blog.image" 
               :alt="blog.title"
               class="w-full h-48 object-cover">
          <div class="p-6">
            <span class="text-sm text-blue-500">{{ blog.category }}</span>
            <h3 class="text-xl font-semibold mt-2">{{ blog.title }}</h3>
            <p class="text-gray-600 mt-2">
              {{ truncateText(blog.content, 100) }}
            </p>
            <div class="mt-4 flex items-center justify-between">
              <span class="text-sm text-gray-500">
                {{ formatDate(blog.created_at) }}
              </span>
              <Link :href="route('blogs.show', blog.id)"
                    class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Read More
              </Link>
            </div>
          </div>
        </div>
        <div v-if="filteredBlogs.length === 0" class="col-span-3 text-center py-12">
          <p class="text-gray-500">No blogs found.</p>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-6">
        <Pagination :links="blogs.links" />
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import Pagination from '../../Components/Pagination.vue'

export default {
  components: {
    Link,
    Pagination
  },

  props: {
    blogs: {
      type: Object,
      required: true
    },
    filters: {
      type: Object,
      default: () => ({})
    }
  },

  setup(props) {
    // State
    const search = ref(props.filters.search || '')
    const selectedCategory = ref(props.filters.category || 'All')
    
    // Categories list
    const categories = [
      'All',
      'Health Tips',
      'Fitness',
      'Wellness',
      'Baby Care',
      'Diabetic Care',
      'Haircare',
      'Pregnancy'
    ]

    // Computed
    const filteredBlogs = computed(() => {
      return props.blogs.data.filter(blog => {
        const matchesSearch = !search.value || 
          blog.title.toLowerCase().includes(search.value.toLowerCase()) ||
          blog.content.toLowerCase().includes(search.value.toLowerCase())
        
        const matchesCategory = selectedCategory.value === 'All' || 
          blog.category === selectedCategory.value

        return matchesSearch && matchesCategory
      })
    })

    // Methods
    const handleSearch = () => {
      Inertia.get(route('blogs.index'), {
        search: search.value,
        category: selectedCategory.value !== 'All' ? selectedCategory.value : null
      }, {
        preserveState: true,
        replace: true
      })
    }

    const selectCategory = (category) => {
      selectedCategory.value = category
      handleSearch()
    }

    const clearSearch = () => {
      search.value = ''
      handleSearch()
    }

    const truncateText = (text, length) => {
      if (text.length <= length) return text
      return text.substr(0, length) + '...'
    }

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric'
      })
    }

    return {
      search,
      selectedCategory,
      categories,
      filteredBlogs,
      handleSearch,
      selectCategory,
      clearSearch,
      truncateText,
      formatDate
    }
  }
}
</script>
