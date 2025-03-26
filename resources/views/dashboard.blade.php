@extends('application')

@section('content')
    <div class="container">
        <h1>Dashboard</h1>

        <table id="posts-table" class="table table-bordered">
            <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Short description</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>

        <v-bottom-sheet v-model="modalVisible" max-width="600px">
            <template v-slot:default="{ close }">
                <v-form @submit.prevent="addPost">
                    <v-text-field v-model="newPost.title" label="Title" required></v-text-field>
                    <v-textarea v-model="newPost.description" label="Short Description" required></v-textarea>
                    <v-btn type="submit" color="primary">Save Post</v-btn>
                    <v-btn @click="modalVisible = false">Cancel</v-btn>
                </v-form>
            </template>
        </v-bottom-sheet>

        <v-btn @click="modalVisible = true" color="primary">Add New Post</v-btn>
    </div>
@endsection

@section('scripts')
    <script>
        new Vue({
            el: '#app',
            data() {
                return {
                    modalVisible: false,
                    newPost: {
                        title: '',
                        description: ''
                    }
                };
            },
            mounted() {
                this.initDataTable();
            },
            methods: {
                initDataTable() {
                    $('#posts-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: '{{ route('posts.get') }}',
                        columns: [
                            { data: 'created_at' },
                            { data: 'title' },
                            { data: 'description' },
                            { data: 'actions', orderable: false, searchable: false }
                        ]
                    });
                },
                addPost() {
                    axios.post('{{ route('posts.store') }}', this.newPost)
                        .then(response => {
                            this.modalVisible = false;
                            this.newPost = { title: '', description: '' };
                            $('#posts-table').DataTable().ajax.reload();
                        });
                }
            }
        });
    </script>
@endsection
