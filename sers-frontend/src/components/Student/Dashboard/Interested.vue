<template>
    <div class = "container-fluid display-container">

        <div class="table-container">
            <div class="table-header">
                <h4>Interested campaigns</h4>
                <p class = "text-muted">Courses which you have indicated interest in.</p>
            </div>
        
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Name</th>
                        <th scope = "col">Class Date</th>
                        <th scope = "col">Deadline</th>
                        <th scope = "col">Status</th>
                    </tr>
                </thead>
                <tbody  v-if="filtered_courses.length != 0" >
                    
                    <tr v-for="course in filtered_courses" :key="course" @click="changeStatus(course)" data-toggle="modal" data-target="#exampleModalCenterTitle">
                        <td><b>{{course.Course_Name}}</b> <br> {{course.Course_Desc}}</td>
                        <td><p> - </p></td>
                        <td>{{course.Vote_End}}</td>
                        <td>
                            <span :class="['badge', 'badge-pill' , course.FCourse_Status]">

                                <span>{{course.FCourse_Status}}</span>

                            </span>
                        </td>

                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <div class="table-header"><h4>No interested courses</h4></div>
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
                        <button type ="button" class="btn btn-danger"  data-dismiss="modal" v-if="current.FCourse_Status == 'ongoing'" @click="removeInterest(current)">Remove Interest</button>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
// INDICATEDINTEREST_URL
// ONGOINGCAMPAIGN_URL
    import Swal from 'sweetalert2'
    import router from '../../../router'
    import axios from 'axios'
    import {INDICATEDINTEREST_URL, REMOVEINTEREST_URL} from '../../../variables'
    export default {
        name: "Interested",
        data(){
            return {
                filtered_courses: [],
                indicated: {},
                current: {}
            }
        },
        methods:{
           getInterestedCourse(){
                    axios.get(INDICATEDINTEREST_URL,
                    {
                        
                        withCredentials: true
                    }
                    ).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>{
                        
                        this.filtered_courses = response.data
                        
                    })

            },
            changeStatus(course){
                this.current = course
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
                        this.getInterestedCourse()
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
            this.getInterestedCourse()
        }
    }
</script>
<style scoped>

    .badge-pill{
        width: 100px;
    }
    :deep(.ongoing){
        background-color: var(--pill-yellow);
        color: white;
    }
    :deep(.offered){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.closed){
        background-color: var(--pill-red);
        color: white;
    }

</style>