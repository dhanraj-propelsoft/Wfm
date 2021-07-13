

import { Container,Icon,Badge, Content, Accordion,Button,Item,  Picker,
    DatePicker ,Text,Textarea,Input,Spinner as Loader ,CardItem,Card, Form,Label,Grid,Col,ListItem,Left,Body,CheckBox,Toast, Right, Subtitle, Title  } from "native-base";


    const  toastr = {
      showToast: (message, duration ) => {
        Toast.show({
          text: message,
          duration,
          position: 'bottom',
          textStyle: { textAlign: 'center' },
        });
      },
    };
    export default toastr;