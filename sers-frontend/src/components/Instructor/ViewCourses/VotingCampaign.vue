<template>
    <div class = "container-fluid display-container">
        <div class="row sub-tabs tabs">
                <p :class="tabs.current? 'active': ''" @click="toggleTab('current')">Current</p>
                <p :class="tabs.ended? 'active': ''" @click="toggleTab('ended')">Ended</p>
        </div>
        <div class="table-container" v-if="tabs.current">
            <div class="table-header">
                <h4>Courses Open For Indicating Interest</h4>
                <p class = "text-muted">Ongoing campaign</p>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col"># Current Interested</th>
                        <th scope = "col"># of Campaign Runs</th>
                        <th scope = "col">Vote End
                            <br>
                            <select v-model="selected_current">
                                <option v-for="year in current_years" :key="year">
                                    {{year}}
                                </option>
                            </select>
                        </th>
                        <th scope = "col">Status</th>
                    </tr>
                </thead>
                <tbody v-if="filtered_courses.length > 0">
                    <template v-for="course in filtered_courses" :key="course">
                         {{getCurrentYears(course.Vote_End)}}
                        <tr v-if="selected_current=='All' || selected_current == getYear(course.Vote_End)">
                            <td><b>{{course.Course_ID}}</b> <br> {{course.Course_Name}}</td>
                            <td>{{course.No_Interest}}</td>
                            <td>{{course.No_Campaigns}}</td>
                            <td><br>{{course.Vote_End}}</td>
                            <td><br> <span :class="['badge', 'badge-pill', course.FCourse_Status]">
                                {{course.FCourse_Status}}
                                </span>
                            </td>

                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="table-container" v-else>
            <div class="table-header">
                <h4>Ended Course Campaigns</h4>
                <p class = "text-muted">Overview all the ended campaigns.</p>
            </div>
            <table class = "table">
                <thead>
                     <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col"># Current Interested</th>
                        <th scope = "col"># of Campaign Runs</th>
                        <th scope = "col">
                            Vote End 
                            <br>
                            <select v-model="selected_ended">
                                <option v-for="year in ended_years" :key="year">
                                    {{year}}
                                </option>
                            </select>
                        </th>
                        <th scope = "col">Status</th>
                    </tr>
                </thead>
                <tbody v-if="filtered_courses_closed.length > 0">
                    <template v-for="course in filtered_courses_closed" :key="course">
                        {{getEndedYears(course.Vote_End)}}
                        <tr v-if="selected_ended=='All' || selected_ended == getYear(course.Vote_End)">
                    
                            <td><b>{{course.Course_ID}}</b> <br> {{course.Course_Name}}</td>
                            <td>{{course.No_Interest}}</td>
                            <td>{{course.No_Campaigns}}</td>
                            <td><br>{{course.Vote_End}}</td>
                            <td><br> <span :class="['badge', 'badge-pill', course.FCourse_Status]">
                                {{course.FCourse_Status}}
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

    </div>
</template>
<script>
    import axios from 'axios'
    import {ONGOINGCAMPAIGN_URL, CLOSEDCAMPAIGN_URL} from '../../../variables'
    export default{
        name: "Campaign",
        data(){
            return{
                filtered_courses:[],
                filtered_courses_closed: [],
                current_years: ['All'],
                ended_years: ['All'],
                selected_current: 'All',
                selected_ended: 'All',
                tabs:{
                    current: true,
                    ended: false
                },
            }
        },
        props:{
        },
        methods:{
            toggleTab(tab){
                for (const property in this.tabs){
                    property == tab ? this.tabs[property] = true : this.tabs[property] = false
                }
            },
            getOngoingFilteredCourses(){
                axios.get(ONGOINGCAMPAIGN_URL).then(response => (
                    this.filtered_courses = response.data,
                    console.log(response.data)
                ))
            },
            getClosedCampaign(){
                axios.get(CLOSEDCAMPAIGN_URL).then(response => (
                    this.filtered_courses_closed = response.data
                ))
            },
            getCurrentYears(vote_end){
                var year = vote_end.split('-')[0]
                if (!this.current_years.includes(year)){
                    
                    this.current_years.push(year)
                }
            },
            getEndedYears(vote_end){
                var year = vote_end.split('-')[0]
                if (!this.ended_years.includes(year)){
                    
                    this.ended_years.push(year)
                }
            },
            getYear(date){
                return date.split('-')[0]
            }
        },
        mounted(){
            this.getOngoingFilteredCourses()
            this.getClosedCampaign()
        },
        updated(){
        
            
        },
        computed(){
        }

    }
</script>
<style scoped>


    .sub-tabs{
        margin: 0;
    }
    .badge-pill{
        width: 100px;
        cursor: pointer;
    }
    :deep(.offered){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.closed){
        background-color: var(--pill-red);
        color: white;
    }
    :deep(.ongoing){
        background-color: var(--pill-yellow);
        color: white;
    }
    .close-campaign{
        background-color: var(--lighter-gray);
        color: var(--pill-red);
    }
    .offer-campaign{
        background-color: var(--lighter-gray);
        color: var(--pill-blue);
    }
</style>