import React, {useEffect, useState} from 'react';
import {Stack} from '@mui/system';
import PostCard from './PostCard';
import {Button} from '@mui/material';
import ScrollTopButton from './ScrollTopButton';
import BackAdress from '../BackAdress';
import {useNavigate} from "react-router-dom";

export default function MarksPage() {
    const [postsTotal, setPostsTotal] = useState(undefined);
    const [posts, setPosts] = useState([]);
    const [pageSize, setPageSize] = useState<number>(5);

    const navigate = useNavigate();

    const offset = 0;

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
        return res['auth'];
    }

    async function redirectIfNotAuth() {
        const test: any = await fetchAuth();
        if (!test) {
            navigate("/auth")
        } else {
            fetchMarks(pageSize, offset).then((posts) => {
                setPostsTotal(posts.count);
                setPosts(posts.posts);
            });
        }
    }

    async function fetchMarks(pageSize: any, offset: any) {
        let res = await fetch(`http://${BackAdress}/api/marks?limit=${pageSize}&offset=${offset}`
            , {
                credentials: 'include',
                method: 'GET',
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
        return res;
    }

    useEffect(() => {
        redirectIfNotAuth();
    }, [pageSize, offset])

    async function loadNewPosts() {
        setPageSize((pageSize) => pageSize + 5);
    }


    return (
        <Stack spacing={3} alignItems={'center'} paddingBottom={'30px'}>
            <Stack spacing={4} className="s" alignItems={'center'} paddingTop={'90px'} width={'100%'}>
                {posts.map(function ({id, title, content, user_id, image, date, avatar, name}) {
                    return <PostCard key={id} id={id} title={title} content={content} userId={user_id}
                                     image={image} date={date} isAuth={true} userName={name}
                                     userAvatar={avatar}/>
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
