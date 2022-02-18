<template>
    <div class="container-fluid  display-container" v-if="access">
        <Back/>
        <div class="table-container">
            <div class="table-header">
                <h4><i class="fas fa-plus-circle mr-2"></i>Add a course</h4>
                <br/>
                <form>
                    <div class="form-group">
                        <label for="course-id">Course ID</label>
                        <input type="text" class="form-control" id="course-id" placeholder="Course ID" v-model="course.course_id">
                        <small id="course-id-alert" class="form-text" v-if="alert != ''">Course ID already exists</small>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="course-name">Course Name</label>
                        <input type="text" class="form-control" id="course-name" placeholder="Course Name"  v-model="course.course_name">
                    </div>
                    <label for="course-desc">Course Description</label>
                    <textarea class="form-control" rows="10" id="course-desc" placeholder="Course Description🖊️"  v-model="course.course_desc"></textarea>
                    <br/>
                    <label for="course-cat" >Course Category</label>
                    <select class="form-control" id="course-cat"  v-model="course.course_cat">
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
    import Swal from 'sweetalert2'
    import {ADDCOURSE_URL, GETUSERROLE_URL} from "../../variables"
    import router from "../../router"
    export default{
        components:{
            Back
        },
        data(){
            return {
                course:{
                    course_id: "",
                    course_name: "",
                    course_desc: "",
                    course_cat: "Basic",
                },
                alert: "",
                access: false
            }
        },
        methods:{
            submit(){
                axios.post(ADDCOURSE_URL,{
                    'course': this.course
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    
                    
                    if (response.data == "Course ID exists"){
                        Swal.fire({
                            icon: 'error',
                            title: 'Course ID exists',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    }
                    else{
                        Swal.fire({
                            icon: 'success',
                            title: 'Course successfully created',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        router.push('/')
                    }

                })
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    
                    if (response.data != 2 && response.data != 4){
                        this.redirectToCourse()
                        

                    }else{
                        this.access = true
                        
                    }
                })
            },
            redirectToCourse(){
                let timerInterval
                Swal.fire({
                icon: 'error',
                title: 'Access denied!',
                html: 'You do not have the access rights! Redirecting...',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                })
                router.push('/courses')
            },
        },
        mounted(){
            this.checkUserRole()
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