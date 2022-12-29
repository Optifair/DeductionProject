import React, {useEffect, useState} from 'react';
import {Stack} from '@mui/system';
import PostCard from './PostCard';
import {Button} from '@mui/material';
import ScrollTopButton from './ScrollTopButton';
import BackAdress from '../BackAdress';
import AddPostForm from "./AddPostForm";

export default function PostsListPage() {
    const [postsTotal, setPostsTotal] = useState(undefined);
    const [posts, setPosts] = useState([]);
    const [pageSize, setPageSize] = useState<number>(5);
    const [authState, setAuthState] = useState<boolean>(false);

    const offset = 0;

    async function fetchPosts() {
        const res = await fetch(
            `http://${BackAdress}/api/posts?limit=${pageSize}&offset=${offset}`
        );

        return await res.json();
    }

    async function fetchAuth() {
        let res = await fetch(`http://${BackAdress}/api/cookieAuth`
            , {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8',
                    'Access-Control-Allow-Credentials': 'true'
                }
            })
            .then((response) => {
                return response.json()
            })
            .then((data) => {
                return data
            });
        return Boolean(Number(res['auth']));
    }

    async function setAuth() {
        const auth = await fetchAuth();
        setAuthState(auth);
    }

    function updateTemplate() {
        fetchPosts().then((posts) => {
            setPostsTotal(posts.count);
            setPosts(posts.posts);
        });
    }

    useEffect(() => {
        setAuth().then(() => updateTemplate());
    }, [pageSize, offset])

    async function loadNewPosts() {
        setPageSize((pageSize) => pageSize + 5);
    }


    return (
        <Stack spacing={3} alignItems={'center'} paddingBottom={'30px'}>

            <Stack spacing={4} className="s" alignItems={'center'} paddingTop={'90px'} width={'100%'}>
                {
                    authState
                    &&
                    <AddPostForm updateTemplate={updateTemplate}></AddPostForm>
                }
                {posts.map(function ({id, title, content, user_id, image, date, avatar, name}) {
                    return <PostCard key={id} id={id} title={title} content={content} userId={user_id}
                                     image={image} date={date} isAuth={authState} userName={name} userAvatar={avatar}/>
                })}
            </Stack>

            {
                posts.length > 1
                &&
                <Button onClick={loadNewPosts} variant="outlined"
                        style={{border: '1px solid ghostwhite', color: 'ghostwhite'}}>Show more</Button>
            }
            <ScrollTopButton/>
        </Stack>

    );

}
