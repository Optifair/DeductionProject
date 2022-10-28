import SubscribeIcon from '@mui/icons-material/Bookmarks';
import { Avatar, Box, Card, CardContent, CardHeader, CardMedia, Divider, IconButton, IconButtonProps, List, styled, Typography } from '@mui/material';
import { red } from '@mui/material/colors';
import Comment from './Comment';


type Params = {
    id: string;
    title: string;
    content: string;
    userId: string;
    image: string;
}

export default function PostWindow ( { id, title, content, userId, image }: Params )
{
    return (
        <Card sx={ { borderRadius: '25px' } }>
            <Box>
                <CardHeader sx={ { paddingBottom: '0px', fontSize: 'large' } }
                    avatar={
                        <Avatar sx={ { bgcolor: red[ 500 ], height: 60, width: 60 } } src='https://bit.ly/broken-link' >
                        </Avatar>
                    }
                    action={
                        <IconButton aria-label="settings">
                            <SubscribeIcon fontSize='large' />
                        </IconButton>
                    }
                    title={
                        <Typography variant="h5">
                            { userId }
                        </Typography>
                    }
                />
                <CardContent sx={ { paddingTop: '5px' } }>
                    <Typography textAlign={ 'center' } variant="h5" paddingBottom={ '10px' }>
                        { title }
                    </Typography>
                    <Typography variant="body1">
                        { content }
                    </Typography>
                </CardContent>
                <Box margin={ 'auto' } maxWidth="70%" paddingBottom={ '20px' }>
                    <CardMedia
                        component="img"
                        image={ image }
                    />
                </Box>
                <Divider />
                <CardContent sx={ { padding: '0' } }>
                    <Typography paragraph padding={ '0px' }>
                        <Comment key={ '0' } id={ '0' } content={ 'Best Comment' } userId={ '1' }></Comment>
                    </Typography>
                </CardContent>
                <CardContent sx={ { padding: '0' } }>
                    <List sx={ { padding: '0' } }>
                        <Comment key={ '1' } id={ '1' } content={ 'comment 1' } userId={ '1' }></Comment>
                        <Comment key={ '2' } id={ '2' } content={ 'comment 3' } userId={ '1' }></Comment>
                        <Comment key={ '3' } id={ '3' } content={ 'comment 3' } userId={ '1' }></Comment>
                    </List>
                </CardContent>
            </Box>
        </Card >
    );
}






