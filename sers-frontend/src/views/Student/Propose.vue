<template>
<!-- Might not need it anymore -->
    <div class = "container header">
        <div class="row">
            <h1>Propose a Course</h1>
        </div>

            <form v-if="$store.state.role!=''">
            <div class="form-group">

                <label for="name">Course Name</label>
                <input type="text" class="form-control" id="name" placeholder="Name" v-model="course.course_name">

                <label for="course-desc">Course Description</label>
                <textarea class="form-control" rows="10" id="course-desc" placeholder="Course Description🖊️"  v-model="course.course_desc"></textarea>
            </div>
            <button type="button" class="btn btn-primary" @click="submit()">Submit</button>
            </form>
            <div class="warning-text row" v-else>
                <p>
                    Please log in <router-link to="/login">here</router-link> to propose a course
                </p>
            </div>

    </div>
</template>
<script>
    import axios from 'axios'
    import router from '../../router'
    import {PROPOSECOURSE_URL} from '../../variables'
    export default {
        data(){
            return {
                course:{
                    course_name: '',
                    course_desc: ''
                }
            }
        },
        methods:{
            submit(){
                axios.post(PROPOSECOURSE_URL,
                {
                    'course': this.course,
                    'user_id': this.$store.state.user_id
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    console.log(response)
                    
                    if (response.data != 'created'){
                        console.log(response)
                    }else{
                        alert('created')
                    }

                })
            }
        },
        mounted(){
            console.log(this.$store.state.role)
            if (this.$store.state.role == 2 ||  this.$store.state.role == 3 || this.$store.state.role == 4){
                router.push('/proposed')
            }

        },
        updated(){
            if (this.$store.state.role == 2 ||  this.$store.state.role == 3 || this.$store.state.role == 4){
                router.push('/proposed')
            }
        }
    }
        
</script>
<style scoped>
    .row{
        display: flex;
        justify-content: center;

    }
    .row h1{
        text-align: center;
        font-family: var(--title-text);
        padding: 20px;
        padding-top: 1em;
        font-weight: bold;
        padding-bottom: 0;
    }
    #hidden-tag{
        display:none;
    }
    .warning-text{
        margin-top:10px;
        font-size:1.2em;
    }
</style>