<template>
  <NavBar />
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Blog Header -->
        <div class="relative mb-4 overflow-hidden rounded shadow-lg">
          <!-- Updated to use ImageHelper for proper URL handling -->
          <img :src="blog.image ? getImageUrl(blog.image) : '/images/no-image.png'"
               :alt="blog.title" 
               class="w-full h-96 object-cover">
          <div class="absolute inset-0 bg-black bg-opacity-30 flex items-end"></div>
          <div class="absolute bottom-0 left-0 p-8 text-white">
            <span class="bg-blue-500 text-white px-4 py-1 rounded-full text-sm">
              {{ blog.category }}
            </span>
            <h1 class="text-4xl font-bold mt-4">{{ blog.title }}</h1>
            <p class="mt-2">{{ formatDate(blog.created_at) }}</p>
          </div>
        </div>

        <!-- Blog Content -->
        <div class="p-8">
          <div class="prose max-w-none" v-html="blog.content"></div>
        </div>
      </div>

      <!-- Related Blogs -->
      <div v-if="relatedBlogs.length > 0" class="mt-12">
        <h2 class="text-2xl font-semibold mb-6">Related Articles</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div v-for="relatedBlog in relatedBlogs" 
               :key="relatedBlog.id" 
               class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition">
            <Link :href="route('blogs.show', relatedBlog.id)">
              <img :src="relatedBlog.image ? getImageUrl(relatedBlog.image) : '/images/no-image.png'" 
                   :alt="relatedBlog.title" 
                   class="w-full h-48 object-cover">
              <div class="p-6">
                <span class="text-sm text-blue-500">{{ relatedBlog.category }}</span>
                <h3 class="text-xl font-semibold mt-2">{{ relatedBlog.title }}</h3>
                <p class="text-gray-600 mt-2">
                  {{ truncateText(relatedBlog.content, 100) }}
                </p>
              </div>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script>
import { Link } from '@inertiajs/vue3'
import NavBar from '@/components/NavBar.vue'
import Footer from '@/components/Footer.vue'

import { imageHelper } from '@/mixins/ImageHelper.js';

export default {
  components: {
    Link,
    NavBar,
    Footer
  },

  props: {
    blog: {
      type: Object,
      required: true
    },
    relatedBlogs: {
      type: Array,
      default: () => []
    }
  },

  setup() {
    // Import image helper method
    const { getImageUrl } = imageHelper.methods;
    
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
      truncateText,
      formatDate,
      getImageUrl
    }
  }
}
</script>

<style scoped>
.prose {
  max-width: 100%;
}

.prose img {
  margin: 2rem auto;
  border-radius: 0.5rem;
}

.prose h2 {
  color: #1a202c;
  font-weight: 700;
  font-size: 1.5rem;
  margin-top: 2rem;
  margin-bottom: 1rem;
}

.prose p {
  color: #4a5568;
  line-height: 1.75;
  margin-bottom: 1.5rem;
}
</style>
