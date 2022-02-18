<template>
    <div class="container-fluid  display-container">
        <Back/>
        <div class="table-container">
            <div class="table-header">
                <h4><i class="fas fa-plus-circle mr-2"></i>Update Course</h4>
                <br/>
                <form>
                    <div class="form-group">
                        <label for="course-id">Course ID</label>
                        <input type="text" class="form-control" id="course-id" placeholder="Course ID" v-model="new_course.course_id">
                        <small id="course-id-alert" class="form-text" v-if="alert != ''">Course ID already exists</small>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="course-name">Course Name</label>
                        <input type="text" class="form-control" id="course-name" placeholder="Course Name"  v-model="new_course.course_name">
                    </div>
                    <label for="course-desc">Course Description</label>
                    <textarea class="form-control" rows="10" id="course-desc" placeholder="Course Description🖊️"  v-model="new_course.course_desc"></textarea>
                    <br/>
                    <label for="course-cat" >Course Category</label>
                    <select class="form-control" id="course-cat"  v-model="new_course.course_cat">
                        <option>Basic</option>
                        <option>Intermediate</option>
                        <option>Advance</option>
                    </select>
                    <br/>
                    <button type="button" class="btn btn-primary" @click="submit()">Add Course</button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import Back from "../../components/Back"
    import axios from "axios"
    import {ADDCOURSE_URL} from "../../variables"
    import router from "../../router"
    export default{
        name: "UpdateCourse",
        components:{
            Back
        },
        data(){
            return {
                new_course:{
                    course_id: "",
                    course_name: "",
                    course_desc: "",
                    course_cat: "Basic",
                },
                alert: ""
            }
        },
        props:{
            course: Object
        },
        methods:{
            submit(){
                axios.post(ADDCOURSE_URL,{
                    'course': this.new_course
                }).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    console.log(response)
                    
                    if (response.data == "Course ID exists"){
                        this.alert = response.data
                    }
                    else{
                        router.push('/')
                    }

                })
            }
        },
        mounted(){
            console.log(this.course)
            if (this.$store.state.role != 2){
                router.push('/courses')
            }
        }
    }
</script>
<style>
    label{
        font-weight:bold;
    }
    #course-id-alert{
        color:var(--pill-red);
    }
</style>