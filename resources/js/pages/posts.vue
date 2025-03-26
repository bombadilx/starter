<template>
  <VContainer>
    <h1>Posts</h1>

    <VTextField
      v-model="search"
      label="Search Posts"
      append-icon="mdi-magnify"
      single-line
      hide-details
    />

    <VDataTable
      :header="headers"
      :items="posts"
      item-key="id"
      :search="search"
      class="elevation-1"
    >
      <template #item.actions="{ item }">
        <VBtn
          color="primary"
          @click="editPost(item)"
        >
          Edit
        </VBtn>
      </template>
    </VDataTable>

    <VDialog
      v-model="dialogAdd"
      max-width="500px"
    >
      <VCard>
        <VCardTitle class="headline">
          Add Post
        </VCardTitle>
        <VCardText>
          <VTextField
            v-model="newPost.title"
            label="Title"
            required
          />
          <VTextarea
            v-model="newPost.description"
            label="Description"
            required
          />
        </VCardText>
        <VCardActions>
          <VBtn
            color="secondary"
            @click="dialogAdd = false"
          >
            Cancel
          </VBtn>
          <VBtn
            color="primary"
            @click="addPost"
          >
            Save
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VDialog
      v-model="dialogEdit"
      max-width="500px"
    >
      <VCard>
        <VCardTitle class="headline">
          Edit Post
        </VCardTitle>
        <VCardText>
          <VTextField
            v-model="editedPost.title"
            label="Title"
            required
          />
          <VTextarea
            v-model="editedPost.description"
            label="Description"
            required
          />
        </VCardText>
        <VCardActions>
          <VBtn
            color="secondary"
            @click="dialogEdit = false"
          >
            Cancel
          </VBtn>
          <VBtn
            color="primary"
            @click="updatePost"
          >
            Save
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>

    <VBtn
      color="primary"
      @click="dialogAdd = true"
    >
      Add Post
    </VBtn>
  </VContainer>
</template>

<script>
export default {
  // eslint-disable-next-line vue/component-api-style
  data() {
    return {
      posts: [],
      headers: [
        { text: 'Title', align: 'start', key: 'title', sortable: true },
        { text: 'Created At', value: 'created_at' },
        { text: 'Actions', value: 'actions', sortable: false },
      ],
      search: '',
      dialogAdd: false,
      dialogEdit: false,
      newPost: {
        title: '',
        description: '',
      },
      editedPost: {
        id: null,
        title: '',
        description: '',
      },
    }
  },
  // eslint-disable-next-line vue/component-api-style
  mounted() {
    this.getPosts()
  },
  // eslint-disable-next-line vue/component-api-style
  methods: {
    async getPosts() {
      try {
        const response = await fetch('/get-posts', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
          },
        })

        if (response.ok) {
          const data = await response.json()

          this.posts = data.data
        }
      } catch (error) {
        console.error('Error fetching posts:', error)
      }
    },

    editPost(post) {
      this.editedPost.id = post.id
      this.editedPost.title = post.title
      this.editedPost.description = post.description
      this.dialogEdit = true
    },

    async updatePost() {
      try {
        const response = await fetch(`/update-post/${this.editedPost.id}`, {
          method: 'PUT',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify(this.editedPost),
        })

        if (response.ok) {
          const data = await response.json()

          this.dialogEdit = false
          this.getPosts()
        }
      } catch (error) {
        console.error('Error updating post:', error)
      }
    },

    async addPost() {
      try {
        const response = await fetch('/add-post', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify(this.newPost),
        })

        if (response.ok) {
          this.dialogAdd = false
          this.newPost.title = ''
          this.newPost.description = ''
          this.getPosts()
        }
      } catch (error) {
        console.error('Error adding post:', error)
      }
    },
  },
}
</script>
