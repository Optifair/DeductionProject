import React, { useEffect, useState } from 'react';
import { useLocation } from "react-router-dom";
import { Container, Heading, Avatar } from '@chakra-ui/react'

export default function SinglePost() {
    const location = useLocation();
    const [postDataId, setPostData] = useState({});
    const [postData, setCurrentPost] = useState(null);


    const fetchCurrentPost = async (id) => {
        const res = await fetch(
            `http://DeductionProject/api/getCurrentTopic?id=${id}`,
            {
                headers: {
                    "Access-Control-Allow-Origin": "*",
                    "Content-Type": "application/json"
                }
            }
        )

        return await res.json();
    }



    useEffect(() => {
        setPostData(location.state);

        fetchCurrentPost(location.state).then((item) => {
            setCurrentPost(item);
        });

        setTimeout(() => {
            if (location.state == null) {
                window.location.href = '/404';
            }
        }, 100)


    }, [location.state]);


    return (
        <>
            {postData != null && <Container maxW='1200px' marginTop={'50px'} alignContent={'center'}>
                <Heading
                    size='lg'
                    textAlign='center'
                >
                    <Avatar src='https://bit.ly/broken-link' name={postData.userId}>
                    </Avatar>

                    {postData.title}
                </Heading>
                <hr />
                <p>
                    {postData.content}
                </p>
                <img src={postData.image} style={{
                    margin: 'auto',
                }} />
            </Container>
            }
        </>
    )
}
