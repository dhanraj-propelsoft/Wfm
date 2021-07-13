import PropTypes from 'prop-types';
import React, { Component } from 'react';
import { StyleSheet,View } from 'react-native';
import { Icon,Text } from 'native-base';
export default class CustomEmptyList extends Component {

        static propType={
            content: PropTypes.string.isRequired,
        }
        
        
        render(){

            const { content }= this.props;

            return (
                <View style={[style.EmptyContainer]} >

                        <Icon name="sad" color="red" />
                        <Text>{content}</Text>
                </View>
            );
        }
}

const style = StyleSheet.create({
    EmptyContainer: {
      flex: 1,
      justifyContent: 'center',
      alignItems: 'center',
  
      height: '100%'
    }
})