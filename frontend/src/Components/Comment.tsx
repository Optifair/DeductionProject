import {Avatar, Card, CardContent, CardHeader, Divider, Typography} from '@mui/material';
import {red} from '@mui/material/colors';

type Params = {
    id: string;
    content: string;
    userId: string;
    date: string;
}


export default function PostCard({id, content, userId, date}: Params) {
    return (
        <Card>
            <CardHeader sx={{paddingBottom: '0px', fontSize: 'large'}}
                        avatar={
                            <Avatar sx={{bgcolor: red[500], height: 45, width: 45}} src='https://bit.ly/broken-link'>
                            </Avatar>
                        }
                        title={
                            <Typography variant="h6">
                                {userId}
                            </Typography>
                        }
            />
            <CardContent sx={{paddingTop: '10px'}}>
                <Typography variant="body1">
                    {content}
                </Typography>
            </CardContent>
            <Divider/>
        </Card>
    );
}






