<template>
    <div class = "container-fluid display-container">

        <div class="table-container">
            <div class="table-header">
                <h4>Ongoing Interest Campaign</h4>
                <p class = "text-muted">Indicate your interest in attending the courses. Courses with high interest counts may open classes.</p>
            </div>
        
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Interest Count</th>
                        <th scope = "col">Deadline</th>
                        <th scope = "col">Status</th>
                    </tr>
                </thead>
                <tbody  v-if="filtered_courses.length != 0" >
                    
                    <tr v-for="course in filtered_courses" :key="course" @click="changeStatus(course)" data-toggle="modal" data-target="#exampleModalCenterTitle">
                        
                        <td><b>{{course.Course_ID}} - {{course.Course_Name}}</b> <br> {{course.Course_Desc}}</td>
                        <td>{{course.No_Interest? course.No_Interest: 0}}</td>
                        <td>{{course.End_Date}}</td>
                        <td class = "text-center">
                            <span :class="['badge', 'badge-pill' , course.IUser_ID == $store.state.user_id? 'indicated':'interested']">

                                <span v-if="course.IUser_ID == $store.state.user_id">Indicated</span>
                                <span v-else>Interested?</span>

                            </span>
                        </td>

                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <div class="table-header"><h4>No courses at the moment</h4></div>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenterTitle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="false"  v-if="filtered_courses.length != 0" >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{current.Course_Desc}}</p>
                        <button type ="button" class="btn btn-danger"  data-dismiss="modal" v-if="current.IUser_ID == $store.state.user_id" @click="removeInterest(current)">Remove Interest</button>
                        <button type ="button" class="btn interested"  data-dismiss="modal" v-else @click="addInterest()">Indicate Interest</button>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
// INDICATEDINTEREST_URL
// ONGOINGCAMPAIGN_URL
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../../router'
    // ALLCOURSESFILTEREDCOURSESTUDENT_URL
    import {ADDINTEREST_URL, REMOVEINTEREST_URL} from '../../variables'
    export default {
        name: "Campaign",
        data(){
            return {
                // filtered_courses: [],
                indicated: {},
                current: {}
            }
        },
        props:{
            filtered_courses: Array
        },
        methods:{
        //    getOngoingFilteredCourses(){
        //         axios.post(ALLCOURSESFILTEREDCOURSESTUDENT_URL,
        //         {},
        //         {
        //             withCredentials:true
        //         }
        //         ).then(response => {
        //             console.log(response)
        //             if (response.data === 'no rights'){
        //                 this.redirectToCourse()
        //             }else {
        //                 this.filtered_courses = response.data
        //             }
                    
        //         })

        //     },

            changeStatus(course){
                this.current = course
            },

            addInterest(){

                axios.post(ADDINTEREST_URL,{
                    'course_id': this.current.Course_ID,
                    'vote_start': this.current.Vote_Start,
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data === 'no rights'){
                        this.redirectToCourse()
                    }else{
                        this.successMessage('Successfully indicated interest')
                        this.$parent.getFilteredCourse()
                    }   
                    
                })

            },
            removeInterest(course){
                axios.post(REMOVEINTEREST_URL,{
                    'course_id': course.Course_ID,
                    'vote_start': course.Vote_Start
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data === 'no rights'){
                        this.redirectToCourse()
                    }else{
                        this.successMessage('Successfully removed interest')
                        this.$parent.getFilteredCourse()
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
            successMessage(msg){
                Swal.fire({
                    icon: 'success',
                    title: msg,
                    showConfirmButton: false,
                    timer: 1000
                })
            }

        },
        mounted(){

        }
    }
</script>
<style scoped>

    .badge-pill{
        width: 100px;
    }
    :deep(.indicated){
        background-color: var(--dark-gray);
        color: white;
    }
    :deep(.interested){
        background-color: var(--pill-yellow);
        color: white;
    }

</style>