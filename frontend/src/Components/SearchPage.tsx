import React, {useEffect, useState} from 'react';
import {Stack} from '@mui/system';
import PostCard from './PostCard';
import {useParams} from 'react-router-dom';
import ScrollTopButton from './ScrollTopButton';
import {alpha, Button, InputBase, styled, TextField} from '@mui/material';
import BackAdress from '../BackAdress';
import SearchIcon from '@mui/icons-material/Search';
import {LocalizationProvider} from '@mui/x-date-pickers/LocalizationProvider';
import {AdapterDayjs} from '@mui/x-date-pickers/AdapterDayjs';
import {DateTimePicker} from '@mui/x-date-pickers/DateTimePicker';
import dayjs, {Dayjs} from "dayjs";

const Search = styled('div')(({theme}) => ({
    position: 'relative',
    borderRadius: theme.shape.borderRadius,
    backgroundColor: alpha(theme.palette.common.white, 0.15),
    '&:hover': {
        backgroundColor: alpha(theme.palette.common.white, 0.25),
    },
    marginLeft: 0,
    width: '50%',

}));

const SearchIconWrapper = styled('div')(({theme}) => ({
    padding: theme.spacing(0, 2),
    height: '100%',
    position: 'absolute',
    pointerEvents: 'none',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
}));

const StyledInputBase = styled(InputBase)(({theme}) => ({
    color: 'inherit',
    width: '100%',
    '& .MuiInputBase-input': {
        padding: theme.spacing(1, 1, 1, 0),
        paddingLeft: `calc(1em + ${theme.spacing(4)})`,
        transition: theme.transitions.create('width'),
        width: '100%',

    },
}));


export default function SearchPage() {
    const params = useParams();
    const keyWord = params.keyWord;

    const [postsTotal, setPostsTotal] = useState(undefined);
    const [posts, setPosts] = useState([]);
    const [pageSize, setPageSize] = useState<number>(5);
    const [authState, setAuthState] = useState<boolean>(false);

    const offset = 0;

    const [value, setValue] = useState(keyWord);

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        setValue(event.target.value);
    };
    const [valueStartDate, setValueStartDate] = React.useState<Dayjs | null>(
        dayjs('2014-08-18 21:11:54'),
    );

    const [valueEndDate, setValueEndDate] = React.useState<Dayjs | null>(
        dayjs('2034-08-18 21:11:54'),
    );

    const handleChangeStartDate = (newValueStartDate: Dayjs | null) => {
        if (newValueStartDate?.isValid()) {
            setValueStartDate(newValueStartDate);
        }
    };

    const handleChangeEndDate = (newValueEndDate: Dayjs | null) => {
        if (newValueEndDate?.isValid()) {
            setValueEndDate(newValueEndDate);
        }
    };

    async function fetchPosts(pageSize: any, offset: any, keyValue: any = keyWord,
                              startDate: any = '2014-08-18 21:11:54', endDate: any = '2034-08-18 21:11:54') {
        var res = await fetch(
            `http://${BackAdress}/api/searchPosts?limit=${pageSize}&offset=${offset}&key=${keyValue}&startDate=${startDate}&EndDate=${endDate}`
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

    function formatDate(date: any) {
        date = date.substring(0, date.length - 5)
        date = date.replace('T', ' ')
        return date;
    }

    async function setAuth() {
        const auth = await fetchAuth();
        setAuthState(auth);
    }

    useEffect(() => {
        setAuth();
        fetchPosts(pageSize, offset, value, formatDate(valueStartDate?.toISOString()),
            formatDate(valueEndDate?.toISOString())).then((posts) => {
            setPostsTotal(posts.count);
            setPosts(posts.posts);
        });
    }, [pageSize, offset, value, valueStartDate, valueEndDate])

    async function loadNewPosts() {
        setPageSize((pageSize) => pageSize + 5);
    }


    return (
        <Stack spacing={2} alignItems={'center'} paddingBottom={'30px'}>
            <Stack spacing={4} className="s" alignItems={'center'} paddingTop={'90px'} width={'100%'}>
                <Search>
                    <SearchIconWrapper>
                        <SearchIcon/>
                    </SearchIconWrapper>
                    <StyledInputBase
                        onChange={handleChange}
                        defaultValue={keyWord}
                    />
                </Search>
                <LocalizationProvider dateAdapter={AdapterDayjs}>
                    <Stack spacing={3} direction="row">
                        <DateTimePicker
                            label="From"
                            value={valueStartDate}
                            onChange={handleChangeStartDate}
                            renderInput={(params) => <TextField {...params} />}
                        />

                        <DateTimePicker
                            label="To"
                            value={valueEndDate}
                            onChange={handleChangeEndDate}
                            renderInput={(params) => <TextField {...params} />}
                        />
                    </Stack>
                </LocalizationProvider>
                {posts.map(function ({id, title, content, user_id, image, date, name, avatar}) {
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
