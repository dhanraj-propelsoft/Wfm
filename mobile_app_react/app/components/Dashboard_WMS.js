import React, { Component } from 'react';
import {
  StyleSheet,PixelRatio ,View ,Platform,Dimensions,processColor,ScrollView,WebView,AsyncStorage,ActivityIndicator,AppState 
} from 'react-native';
//import { WebView } from "react-native-webview";
import { Container,
  Header,
  Title,
  Content,
  Button,
  Icon,
  ListItem,
  Text,
  Badge,
  Left,
  Right,
  Body,
  Switch,
  Radio,
  Item,
  Card,
  CardItem,
  Picker,
  Separator,Label, Fab} from 'native-base';
  import { Col, Row, Grid } from "react-native-easy-grid";
import AppHeader from'./appHeader';
import AppFooter from'./appFooter';
import Modal from "react-native-modal";
import ActionButton from 'react-native-action-button';
import DashboardTemp from 'react-native-dashboard';

import {StackNavigator, SafeAreaView} from 'react-navigation';

import {HorizontalBarChart,PieChart} from 'react-native-charts-wrapper';
import Config from 'react-native-config';

const {
  width: SCREEN_WIDTH,
  height: SCREEN_HEIGHT,
} = Dimensions.get('window');

const API_URL=Config.API_URL;


const scale = SCREEN_WIDTH / 320;
export function normalize(size) {
  const newSize = size * scale 
  if (Platform.OS === 'ios') {
    return Math.round(PixelRatio.roundToNearestPixel(newSize))
  } else {
    return Math.round(PixelRatio.roundToNearestPixel(newSize)) - 2
  }
}

export default class Dashboard extends Component {
  constructor(props) {
    super(props); 
    this.params = this.props.navigation.state.params;
    this.state = {
    Box1: '',
    Box2: '',
    Box3: '',
    Box4: '',
    loading: true,
    Token:'',
    ChartLabels:["Open","Closed","On Hold","Progress"],
    Id:'',
    appState: AppState.currentState,

    legend: {
      enabled: true,
      textSize: 14,
      form: "SQUARE",
      formSize: 14,
      xEntrySpace: 10,
      yEntrySpace: 3,
 
      wordWrapEnabled: false
    },
    data: {},
    data2: {},
    //highlights: [{x: 1, stackIndex: 2}, {x: 2, stackIndex: 1}],
    drawValueAboveBar:{

    },
    xAxis1: {
      valueFormatter: ['satham2'],
      granularityEnabled: true,
      granularity: 1,

      

    },
    xAxis2: {
      valueFormatter: [],
      granularityEnabled: true,
      granularity: 1
    },
    Pie_chartData:{},
    Pie_legend: {
      enabled: true,
      textSize: 15,
      form: 'CIRCLE',

      horizontalAlignment: "RIGHT",
      verticalAlignment: "CENTER",
      orientation: "VERTICAL",
      wordWrapEnabled: true
    },
    
    Pie_description: {
      text: 'Job Card Status',
      textSize: 15,
      textColor: processColor('Black'),

    }
    
    
  };
  }

  
  handleSelect(event) {
    let entry = event.nativeEvent
    if (entry == null) {
      this.setState({...this.state, selectedEntry: null})
    } else {
      this.setState({...this.state, selectedEntry: JSON.stringify(entry)})
    }

    console.log(event.nativeEvent)
  }



  DashboardData = async (personId,Organization_Id)  => {
    // console.log(Organization_Id);
     console.log(`${API_URL}/wms_dashboard/${personId}/${Organization_Id}`);
 
     const ApiToken=await  AsyncStorage.getItem('Token', (err, item) => item);
      
     fetch(`${API_URL}/wms_dashboard/${personId}/${Organization_Id}`,{
       method: 'GET',
       headers: {
           Accept: 'application/json',
           'Content-Type': 'application/json',
           'Authorization':'Bearer '+ApiToken,
       },
     })
       .then((response) => response.json())
       .then((responseJson) => {
        
        console.log(responseJson.data);
        console.log(responseJson.data);
      //  return false;
        let AssignedUserChart=responseJson.data.bar_chart1;
        let VehicleStatus=responseJson.data.bar_chart2;
        let PieChartValue=responseJson.data.pie_chart_data;
        let employeeData = responseJson.data.EmployeeList;
        
        console.log(employeeData);
        let EmployeeList=employeeData;
        let job_status_list=responseJson.data.job_status_list;
      
        let color_object= responseJson.data.pie_chart_colors;
        
        const color_array = color_object.map(function(item,key) {
          $colors=color_object[key];
          //console.log( $colors);
          return  processColor($colors);
        });


        const values_array = PieChartValue.map(function(item,key) {
          return {value: PieChartValue[key][1],label:PieChartValue[key][0]};
        });
        
        const Vehicle_status_array = VehicleStatus.map(function(item,key) {
          return {y: VehicleStatus[key],marker:job_status_list};
        });

       // AssignedUserChart
        
       console.log("Data" +values_array);
        const Job_status_array = AssignedUserChart.map(function(item,key) {
          return {y: item,marker:job_status_list};
        });

     
       // return false;
        const PieChart= {
          dataSets: [{
            values:values_array,
            label: "",
            config: {
              colors: color_array,
              valueTextSize: 12,
              valueTextColor: processColor('white'),
              sliceSpace: 5,
              selectionShift: 13,
              xValuePosition: "INSIDE_SLICE",
              yValuePosition: "INSIDE_SLICE",
              valueFormatter: "#.#'%'",
              valueLineColor: processColor('white'),
              valueLinePart1Length: 0.5
            }
          }],
          };
        
        
        
      

        let Vehicle_ChartData={
              dataSets: [{
                values: Vehicle_status_array,
                label: '',
                config: {
                  colors: [processColor('#ff9933'), processColor('#33cc33'), processColor('#ff3300'), processColor('#ffff00')],
                  stackLabels: job_status_list,
                  axisMaximum:1,
                  axisMinimum:0
                }
              }]
  
        };


        let JobStatus_ChartData={
                dataSets: [{
                  values: Job_status_array,
                  label: '',
                  config: {
                    colors: [processColor('#ff9933'), processColor('#33cc33'), processColor('#ff3300'), processColor('#ffff00')],
                    stackLabels: job_status_list,
                    axisMaximum:1,
                    axisMinimum:0
                  }
                }]

          };
          
        let AssignedUserList={ 
              valueFormatter: EmployeeList,
              granularityEnabled: true,
              granularity: 1
            };          

        let Chart2Labels={ 
              valueFormatter: responseJson.data.register_vechicles,
              granularityEnabled: true,
              granularity: 1
            };

         this.setState({Box1: responseJson.data.box1,Box2: responseJson.data.box2,Box3: responseJson.data.box3,Box4: responseJson.data.box4,data:Vehicle_ChartData,data2:JobStatus_ChartData,xAxis1:AssignedUserList,xAxis2:Chart2Labels,Pie_chartData:PieChart, loading: false});
       
     }).catch((error) => {
      // alert('Not working');
       console.warn('error', error);
       this.setState({regErr: error});
       
   });;
 
   };

   saveOrgID = async(userId)  => {
    try {
     let Id=userId.toString();
    
      await AsyncStorage.setItem('org_id', Id);
    } catch (error) {
      // Error retrieving data
      console.log("cannot Save");
    }
  };
  
  


  componentDidMount()
  {
          
          const {state} = this.props.navigation;
          console.log(state);
          const Token = AsyncStorage.getItem('Token');
         
         
          let org_id=state.params.org_id;
          
          this.saveOrgID(org_id);
          
          
          if(Token){
          
            AsyncStorage.getItem('PersonId').then(PersonId => {
          
           
          // let org_id1=this.getOrgID();  
         
            let FetchData = this.DashboardData(PersonId,org_id);
         //  AppState.addEventListener('change',this.DashboardData(PersonId,org_id));
          
          });

            
          }else{
          
          }

  }



  render() {
    const { 
      Box1,
      Box2,
      Box3,
      Box4,
      loading } = this.state;
   
    console.log("Chart Data"+this.state.Pie_chartData);

    if(!loading) { 
   

    return (
      <ScrollView>
          <View style={styles.container}> 
                     
                     
                     <Grid style={{margin:5}}>
                         <Col style={[styles.DashboardStat1]}><Text style={[styles.small,styles.StatValue]} >{Box1}</Text><Text style={[styles.mini,styles.StatLabel]} >New,First Inspected,Estimation Pending</Text></Col>
                         <Col style={[styles.DashboardStat2]}><Text style={[styles.small,styles.StatValue]} >{Box2}</Text><Text style={[styles.mini,styles.StatLabel]} >Estimation Approved,Work In Progress </Text></Col>
                     </Grid>
                     <Grid style={{margin:5}}>
                         <Col style={[styles.DashboardStat3]}><Text style={[styles.small,styles.StatValue]} >{Box3}</Text><Text style={[styles.mini,styles.StatLabel]} > Final Inspected,Vehicle Ready </Text></Col>
                         <Col style={[styles.DashboardStat4]}><Text style={[styles.small,styles.StatValue]} >{Box4}</Text><Text style={[styles.mini,styles.StatLabel]} >Closed</Text></Col>
                     </Grid>
  
       
    
            </View>
            <Container style={[styles.container]}>
          
                        <View style={styles.container_chart}> 
                                    <Text style={{padding:5}}>Assigned User work status</Text>
                                         
                                    <HorizontalBarChart
                                        style={styles.chart}
                                        xAxis={this.state.xAxis1}
                                        data={this.state.data2}
                                        legend={this.state.legend}
                                        drawValueAboveBar={false}
                                        marker={{
                                          enabled: true,
                                          markerColor: processColor('#F0C0FF8C'),
                                          textColor: processColor('white'),
                                          markerFontSize: 14,
                                        }}
                                        //highlights={this.state.highlights}
                                        onSelect={this.handleSelect.bind(this)}
                                        onChange={(event) => console.log(event.nativeEvent)}
                                      />
                       
         
                        </View>
                        <View style={styles.container_chart}> 
                                    <Text style={{padding:5}}>Vehicle Status</Text>
                                         
                                    <HorizontalBarChart
                                        style={styles.chart}
                                        xAxis={this.state.xAxis2}
                                        data={this.state.data}
                                        legend={this.state.legend}
                                        drawValueAboveBar={false}
                                        marker={{
                                          enabled: true,
                                          markerColor: processColor('#F0C0FF8C'),
                                          textColor: processColor('white'),
                                          markerFontSize: 14,
                                        }}
                                        //highlights={this.state.highlights}
                                        onSelect={this.handleSelect.bind(this)}
                                        onChange={(event) => console.log(event.nativeEvent)}
                                      />
                          </View>
                          <View style={styles.container_chart}> 
                                    <Text style={{padding:5}}>Jobcard Status</Text>
                                      <PieChart
                                      style={styles.chart}
                                      logEnabled={true}
                                      chartBackgroundColor={processColor('white')}
                                      chartDescription={this.state.Pie_description}
                                      data={this.state.Pie_chartData}
                                      legend={this.state.Pie_legend}
                                      highlights={this.state.highlights}

                                      entryLabelColor={processColor('white')}
                                      entryLabelTextSize={12}
                                      drawEntryLabels={true}

                                      rotationEnabled={true}
                                      rotationAngle={45}
                                      usePercentValues={true}
                                      styledCenterText={{text:'', color: processColor('white'), size: 12}}
                                      centerTextRadiusPercent={100}
                                      holeRadius={40}
                                      holeColor={processColor('#f0f0f0')}
                                      transparentCircleRadius={45}
                                      transparentCircleColor={processColor('#f0f0f088')}
                                      maxAngle={360}
                                      onSelect={this.handleSelect.bind(this)}
                                      onChange={(event) => console.log(event.nativeEvent)}
                                    />
                            </View>
                    
              </Container>
      </ScrollView>
      );
     
       }else{

              return <ActivityIndicator />
      }
    
  }
}
const styles = StyleSheet.create({
    noBorder: {
     borderBottomWidth:0
    },
    colWidth: {
      flex:1
     },
     actionButtonIcon: {
      fontSize: 20,
      height: 22,
      color: 'white',
    },
    container: {
      flex: 1,
      backgroundColor:'rgb(246, 246, 246)'
      
    },
    chart: {
      flex: 1
    },
    container: {
      flex: 1
    },
    container_chart:{
     
      borderRadius:5,
      elevation: 5,
      flex:0.9,
      margin:10,
      backgroundColor:'white'
    },
    StatValue:{
      textAlign:"center",
      color:'white',
      fontWeight:'bold',
      padding:2
    },
    StatLabel:{
      textAlign:"center",
      color:'white'
    },
    mb: {
      marginBottom: 15
    },
    DashboardStat1:{
       backgroundColor: '#5cb85c',
        height: 100 ,
        margin:5,
        borderRadius:10,
        justifyContent:'center',
        elevation: 5,
    },
    
    DashboardStat2:{
      backgroundColor: '#3498db',
       height: 100 ,
       margin:5,
       borderRadius:10,
       justifyContent:'center'

   },
   DashboardStat3:{
    backgroundColor: '#ff7800',
     height: 100 ,
     margin:5,
     borderRadius:10,
     justifyContent:'center'

 },
 DashboardStat4:{
  backgroundColor: '#5483b6',
   height: 100 ,
   margin:5,
   borderRadius:10,
   justifyContent:'center'

},
  mini: {
    fontSize: normalize(12),
  },
  small: {
    fontSize: normalize(15),
  },
  medium: {
    fontSize: normalize(17),
  },
  large: {
    fontSize: normalize(20),
  },
  xlarge: {
    fontSize: normalize(24),
  },
  });
module.export = Dashboard;